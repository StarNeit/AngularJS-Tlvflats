<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 24/05/16
 * Time: 14:29
 */

namespace AppBundle\Service;


use AppBundle\Entity\Booking;
use AppBundle\Service\Channels\ChannelService;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;

class SyncListener implements NotifyListenerInterface
{

    //"@logger", "@beds24", "@doctrine.orm.entity_manager", "@service_container", "@booking"
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Beds24
     */
    private $beds24;
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var Container
     */
    private $container;
    /**
     * @var BookingService
     */
    private $bookingService;
    /**
     * @var ChannelService
     */
    private $channelService;


    /**
     * SyncListener constructor.
     */
    public function __construct(Logger $logger, Beds24 $beds24, EntityManager $entityManager, Container $container, BookingService $bookingService, ChannelService $channelService)
    {
        $this->logger = $logger;
        $this->beds24 = $beds24;
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->bookingService = $bookingService;
        $this->channelService = $channelService;
    }

    public function notify(Booking $booking, $status)
    {
        $channelServices = $this->channelService->getServices();
        if (empty($booking->getChannel())) {
            foreach ($channelServices as $channelService) {
                $this->syncBookingToChannel($booking, $status, $channelService);
            }
        } else {
            /** @var ChannelServiceInterface $channelService */
            foreach ($channelServices as $channelService) {
                if ($channelService->getChannel()->getId() === $booking->getChannel()->getId())
                    continue;
                $this->syncBookingToChannel($booking, $status, $channelService);
            }
            if (empty($booking->getBookId()))
                $this->syncBookingToBeds24($booking, $status);
        }
    }

    public function interestedIn(Booking $booking, $status)
    {
        return true;
    }

    private function syncBookingToChannel(Booking $booking, $status, ChannelServiceInterface $channelService)
    {
        if ($status == "new") {
            $channelService->addBooking($booking);
        } elseif ($status == "modify") {
            $channelService->modifyBooking($booking);
        } elseif ($status == "cancel") {
            $channelService->removeBooking($booking);
        }
    }

    private function syncBookingToBeds24(Booking $booking, $status)
    {
        if ($status == "new") {
            $res = $this->beds24->setBooking($booking->toBeds24Booking());
            if (isset($res["bookId"])) {
                $booking->setBookId($res["bookId"]);
                $this->entityManager->persist($booking);
                $this->entityManager->flush($booking);
                $this->logger->error(json_encode($res));
            }
//            $this->logger->error(json_encode($booking->toBeds24Booking()));
        } elseif ($status == "modify") {
            //modified
        } elseif ($status == "cancel") {
            //deleted
        }
    }
}