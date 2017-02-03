<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 21/02/16
 * Time: 15:52
 */

namespace AppBundle\Service;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Property;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;


class BookingService
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Beds24
     */
    private $beds24;

    /**
     * NotifyService constructor.
     */
    public function __construct(Logger $logger, \Swift_Mailer $mailer, Container $container, EntityManager $em, Beds24 $beds24)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->container = $container;
        $this->em = $em;
        $this->beds24 = $beds24;
    }

    public function findBookingByBookId($bookId, $propertyId)
    {
        /** @var Booking $booking */
        $booking = $this->getSavedBooking($bookId);

        if (isset($booking)) {
            $beds24_booking = $this->beds24->getBooking($bookId, $booking->getRoom()->getProperty()->getOwner()->getApiKey(), $booking->getRoom()->getProperty()->getPropKey());
            if (isset($beds24_booking['error']) || count($beds24_booking) == 0) {
                throw new \Exception("Was not able to find booking on beds24: $bookId");
            }
            $booking->setFromBeds24($beds24_booking[0], $booking->getRoom());
            $this->em->persist($booking);
            $this->em->flush();
        } else {
            $booking = new Booking();

            $property = null;
            if (isset($propertyId)) {
                $property = $this->em->createQuery("select p from AppBundle:Property p where p.propid=:propId")->setParameter("propId", $propertyId)->getOneOrNullResult();
            }

            $beds24_booking = $this->beds24->findBooking($bookId, $property, $this->getPropApiKeyPairs());
            if ($beds24_booking == null || isset($beds24_booking->error)) {
                throw new \Exception("Booking $bookId not found");
            }

            $roomId = $beds24_booking[0]["roomId"];
            $room = $this->findRoom($roomId);

            if ($room == null)
                throw new \Exception("Was not able to find room: $roomId from beds24 responce");

            $booking->setFromBeds24($beds24_booking[0], $room);

            $this->em->persist($booking);
            $this->em->flush();
        }

        return $booking;
    }


    private function getPropApiKeyPairs()
    {
        $resarray = array();
        $res = $this->em->createQuery("select p,o from AppBundle:Property p join p.owner o")->getResult();
        foreach ($res as $prop) {
            /** @var Property $prop */
            $resarray[$prop->getPropKey()] = $prop->getOwner()->getApiKey();
        }

        return $resarray;
    }

    public function importBookings(Property $property)
    {
        $warnings = array();
        try {
            $beds24_bookings = $this->beds24->getBookings($property->getOwner()->getApiKey(), $property->getPropKey());
        } catch (\Exception $e) {
            array_push($warnings, array("type"=>"error", "message"=>"Failed to fetch data for ".$property->getTitle()));
            return $warnings;
        }

        foreach ($beds24_bookings as $beds24_booking) {
            /** @var Booking $booking */
            $booking = $this->getSavedBooking($beds24_booking['bookId']);

            if (!isset($booking)) {
                $booking = new Booking();

                $room = $this->findRoom($beds24_booking['roomId']);
                if (!isset($room)) {
                    array_push($warnings, array(
                        "type" => "error",
                        "message" => "Unable to find room specified in booking # " . $beds24_booking['bookId'] . ". roomId: " . $beds24_booking['rooomId']));
                }
                $booking->setRoom($room);
            }
            $booking->setFromBeds24($beds24_booking);
            $this->em->persist($booking);

            array_push($warnings, array("type" => "notice", "message" => "booking for " . $booking->getRoom()->getTitle() . " imported. " . $booking->getGuestName() . "(".
                $booking->getFirstNight()->format("Y-m-d")." - ".$booking->getLastNight()->format("Y-m-d").")"));
        }
        $this->em->flush();

        return $warnings;
    }

    /**
     * @param $bookId
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSavedBooking($bookId)
    {
        $booking = $this->em->createQuery("select b from AppBundle:Booking b where b.bookId=:bookId")->setParameter("bookId", $bookId)->getOneOrNullResult();
        return $booking;
    }

    /**
     * @param $roomId
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findRoom($roomId)
    {
        $room = $this->em->createQuery("select r from AppBundle:Room r where r.roomid=:roomId")->setParameter("roomId", $roomId)->getOneOrNullResult();
        return $room;
    }

    public function findBookingsFromChannel($channel, $bookingsFromChannel)
    {
        $ids = array();
        /** @var Booking $bookingFromChannel */
        foreach ($bookingsFromChannel as $bookingFromChannel) {
            $ids[] = $bookingFromChannel->getApiReference();
        }

        $bookings = $this->em->createQuery("select b from AppBundle:Booking b where b.channel = :channel and b.apiReference in (:ids)")
            ->setParameter("channel", $channel)
            ->setParameter("ids", $ids)
            ->getResult();

        return $bookings;
    }

    public function findBookingsInRange($propertyId, $dateFrom, $dateTo)
    {
        $bookings = $this->em->createQuery("select b from AppBundle:Booking b join b.room r join r.property p ".
                                           "where p.id=:propId and (b.firstNight BETWEEN :dateFrom and :dateTo or ".
                                                                       "b.lastNight BETWEEN :dateFrom and :dateTo)")
            ->setParameter("propId", $propertyId)
            ->setParameter("dateFrom", $dateFrom)
            ->setParameter("dateTo", $dateTo)
            ->getResult();
        
        return $bookings;
    }
}