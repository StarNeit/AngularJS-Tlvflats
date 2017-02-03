<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 23/05/16
 * Time: 11:42
 */

namespace AppBundle\Service\Channels;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Channel;
use AppBundle\Entity\ChannelOption;
use AppBundle\Entity\ChannelProperty;
use AppBundle\Entity\ChannelRoom;
use AppBundle\Entity\OptionsTemplate;
use AppBundle\Service\ChannelServiceInterface;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;

class MyTlvChannelService implements ChannelServiceInterface
{
    /** @var  Channel */
    protected $channel;

    /** @var Logger */
    private $logger;
    /** @var EntityManager */
    private $em;
    /** @var \Pest */
    private $service;

    /**
     * MyTlvChannelService constructor.
     */
    public function __construct(Logger $logger, EntityManager $em, $endpoint)
    {
        $this->logger = $logger;
        $this->em = $em;
        $this->service = new \PestJSON($endpoint);
    }


    /** @return int */
    public function getCapabilities()
    {
        return ChannelServiceInterface::PULL
        | ChannelServiceInterface::EDIT_BOOKINGS
        | ChannelServiceInterface::SINGLE_ROOM_PER_PROPERTY
        | ChannelServiceInterface::GET_BOOKINGS_LIST
        | ChannelServiceInterface::GET_ROOMS_LIST;
    }

    /** @return OptionsTemplate */
    public function getOptionsTemplate(Channel $channel)
    {
        return (new OptionsTemplate())->setOptions(array(
            (new ChannelOption())->setChannel($channel)
                ->setName("Secret")
                ->setType("text")
                ->setDescription("Secret key to access my-telaviv api")
        ));
    }

    /** @return array */
    public function getProperties(Channel $channel)
    {
        /** @var array $aparts */
        $aparts = $this->service->get("list_aparts.php");
        $res = array();
        foreach ($aparts as $apart) {
            $apiResponse = json_encode($apart);
            $channelProperty = (new ChannelProperty())
                ->setChannel($channel)
                ->setChannelId($apart["id"])
                ->setTitle($apart["titre"])
                ->setAddress($apart["adresse"])
                ->setDescription($apart["descriptif"])
                ->setApiResponse($apiResponse);
            $room = (new ChannelRoom())
                ->setDescription($apart["descriptif"])
                ->setChannelId($apart["id"])
                ->setTitle($apart["titre"])
                ->setApiResponse($apiResponse)
                ->setProperty($channelProperty);
            $channelProperty
                ->addRoom($room);
            $res[] = $channelProperty;
        }
        return $res;
    }

    /** @return array */
    public function getRooms(ChannelProperty $channelProperty)
    {
        // TODO: Implement getRooms() method.
    }

    /** @return  array */
    public function getBookings(ChannelProperty $channelProperty = null, ChannelRoom $channelRoom = null, \DateTime $dateFrom = null, \DateTime $dateTo = null)
    {
        $params = array();
        if(isset($channelProperty)) {
            $params["appartment_id"] = $channelProperty->getChannelId();
        }
        if(isset($dateFrom)) {
            $params["date_from"] = $dateFrom->format("Y-m-d");
        }
        $bookingsFromChannel = (array) ($this->service->get("list_bookings.php", $params));
        $res = array();
        $this->logger->error(json_encode($bookingsFromChannel));
        foreach ($bookingsFromChannel as $bookingFromChannel) {
            $booking = new Booking();
            $channelBookingId = $bookingFromChannel["id"];
            $booking->setApiReference($channelBookingId);
            $booking->setChannel($this->getChannel());
            $property = $this->findProperty($bookingFromChannel["fkpro"]);
            if(empty($property) || empty($property->getProperty()))
                continue;
            $booking->setRoom($property->getProperty()->getRooms()->get(0));
            $booking->setRoomQty(1);
            $booking->setBookingTime((new \DateTime())->format("Y-m-d"));
            $booking->setFirstNight((new \DateTime($bookingFromChannel["date_debut"]))->format("Y-m-d"));
            $booking->setLastNight((new \DateTime($bookingFromChannel["date_fin"]))->format("Y-m-d"));
            $booking->setNumAdult(1);
            $booking->setGuestName("From my-telaviv.com");
            $res[] = $booking;
        }
        
        return $res;
    }

    /** @return array */
    public function getPrices(Channel $channel, ChannelProperty $channelProperty, ChannelRoom $channelRoom, \DateTime $dateFrom, \DateTime $dateTo)
    {
        // TODO: Implement getPrices() method.
    }

    public function createProperty(ChannelProperty $channelProperty)
    {
        // TODO: Implement createProperty() method.
    }

    public function createRoom(ChannelRoom $room)
    {
        // TODO: Implement createRoom() method.
    }

    public function modifyProperty(ChannelProperty $channelProperty)
    {
        // TODO: Implement modifyProperty() method.
    }

    public function modifyRoom(ChannelRoom $channelRoom)
    {
        // TODO: Implement modifyRoom() method.
    }

    public function addBooking(Booking $booking)
    {
        $this->logger->error("STARTING SYNC TO MyTLV");

        $channelProperties = $this->em->getRepository("AppBundle:ChannelProperty")->findBy(array(
            "property" => $booking->getRoom()->getProperty()
        ));

        if(empty($channelProperties))
            return;

        $channelProperty = $channelProperties[0];

        $token = $this->service->get("token.php");

        $auth = $this->generateAuth($token["token"]);

        $res = $this->service->get("book.php", array(
            "auth"=>$auth,
            "date_from"=>$booking->getFirstNight(),
            "date_to"=>$booking->getLastNight(),
            "apartment_id"=>$channelProperty->getChannelId()
        ));

        $this->logger->error(json_encode($res));

        if(!empty($res["error"])) {
            $this->logger->error($res["error"]);
        }
    }

    public function removeProperty(ChannelProperty $channelProperty)
    {
        // TODO: Implement removeProperty() method.
    }

    public function removeRoom(ChannelRoom $room)
    {
        // TODO: Implement removeRoom() method.
    }

    public function removeBooking(Booking $booking)
    {
        $channelProperties = $this->em->getRepository("AppBundle:ChannelProperty")->findBy(array(
            "property" => $booking->getRoom()->getProperty()
        ));

        if(empty($channelProperties))
            return;

        $channelProperty = $channelProperties[0];

        $token = $this->service->get("token.php");

        $auth = $this->generateAuth($token["token"]);

        $res = $this->service->get("unbook.php", array(
            "auth"=>$auth,
            "date_from"=>$booking->getFirstNight(),
            "date_to"=>$booking->getLastNight(),
            "apartment_id"=>$channelProperty->getChannelId()
        ));

        error_log(json_encode($res));

        if(!empty($res["error"])) {
            $this->logger->error($res["error"]);
        }
    }

    /** @return Channel */
    public function getChannel()
    {
        if(!isset($this->channel)) {
            $channels = $this->em->getRepository("AppBundle:Channel")->findBy(array(
                "serviceName" => (new \ReflectionClass($this))->getShortName()
            ));

            if(!empty($channels)) {
                $this->channel = $channels[0];
            } else {
                $this->channel = new Channel();
                $this->channel->setName($this->getName());
                $this->channel->setServiceName((new \ReflectionClass($this))->getShortName());
                $this->em->persist($this->channel);
                $this->em->flush($this->channel);
            }
        }

        return $this->channel;
    }

    /** @return string */
    public function getName()
    {
        return "MyTlv";
    }

    public function modifyBooking(Booking $booking)
    {
        // TODO: Implement modifyBooking() method.
    }

    private function generateAuth($token)
    {
        return hash("sha512", $this->getChannel()->getOption("Secret")->getVal().$token);
    }

    private function findProperty($fkpro)
    {
        $channelProperties = $this->em->getRepository("AppBundle:ChannelProperty")->findBy(array(
            "channel" => $this->getChannel(),
            "channelId" => $fkpro
        ));

        if(empty($channelProperties))
            return null;

        return $channelProperties[0];
    }
}