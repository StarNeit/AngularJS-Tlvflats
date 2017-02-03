<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 30/05/16
 * Time: 12:02
 */

namespace AppBundle\Service\Channels;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Channel;
use AppBundle\Service\BookingService;
use AppBundle\Service\ChannelServiceInterface;
use AppBundle\Service\CronInterface;
use AppBundle\Service\SyncListener;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\VarDumper\VarDumper;

class CheckPullChannels implements CronInterface
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Container
     */
    private $service_container;
    /**
     * @var Registry
     */
    private $doctrine;
    /**
     * @var BookingService
     */
    private $booking;
    /**
     * @var SyncListener
     */
    private $syncListener;

    private $services = array();

    /**
     * CheckPullChannels constructor.
     */
    public function __construct(Logger $logger, Container $service_container, Registry $doctrine, BookingService $booking, SyncListener $syncListener, $channelServices)
    {
        $this->logger = $logger;
        $this->service_container = $service_container;
        $this->doctrine = $doctrine;
        $this->booking = $booking;
        $this->syncListener = $syncListener;
        foreach ($channelServices as $channelService) {
            if($service_container->has($channelService)) {
                /** @var ChannelServiceInterface $service */
                $service = $this->service_container->get($channelService);
                if($service->getCapabilities() & ChannelServiceInterface::PULL != 0) {
                    $this->logger->error($channelService);

                    $this->services[] = $service;
                }
            }
        }
    }


    /** @return string */
    public function spec()
    {
        return "PT20M";
    }

    public function run()
    {
        $this->logger->error("Starting PULL");
        /** @var ChannelServiceInterface $service */
        foreach ($this->services as $service) {
            $this->logger->error("PULLING from ".$service->getName());
            $bookingsFromChannel = $service->getBookings(null, null, new \DateTime(), null);
            $this->logger->error("Found ".count($bookingsFromChannel)." bookings");
            $notSavedBookings = $this->findNonSaved($service->getChannel(), $bookingsFromChannel);
            $this->logger->error("Found ".count($notSavedBookings)." not saved bookings");
            foreach ($notSavedBookings as $notSavedBooking) {
                $em = $this->doctrine->getEntityManager();
                $em->persist($notSavedBooking);
                $em->flush($notSavedBooking);
                $this->syncListener->notify($notSavedBooking, "new");
            }
        }
    }

    private function findNonSaved(Channel $channel, $bookingsFromChannel)
    {
        $savedBookings = $this->booking->findBookingsFromChannel($channel, $bookingsFromChannel);
        $res = array();
        $savedIds = array();
        /** @var Booking $savedBooking */
        foreach ($savedBookings as $savedBooking) {
            $savedIds[$savedBooking->getApiReference()] = "1";
        }
        /** @var Booking $bookingFromChannel */
        foreach ($bookingsFromChannel as $bookingFromChannel) {
            if(!isset($savedIds[$bookingFromChannel->getApiReference()])) {
                $res[] = $bookingFromChannel;
            }
        }
        
        return $res;
    }
}