<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 04.10.2015
 * Time: 17:24
 */

namespace AppBundle\Service;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Property;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

class NotifyService
{
    private $listener_services = array();
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
     * @var BookingService
     */
    private $bookingService;

    /**
     * NotifyService constructor.
     */
    public function __construct(Logger $logger, $listeners, \Swift_Mailer $mailer, Container $container, EntityManager $em, Beds24 $beds24, BookingService $bookingService)
    {
        foreach ($listeners as $listener) {
            $this->listener_services[] = $container->get($listener);
        }
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->container = $container;
        $this->em = $em;
        $this->beds24 = $beds24;
        $this->bookingService = $bookingService;
    }

    public function notify(Request $request)
    {
        $bookId = $request->get("bookid");
        $status = $request->get("status");
        $propertyId = $request->get('propertyId');

        if (empty($bookId) || empty($status)) {
            return "Please do not use this manually";
        }
        $this->logger->error("before booking service");
        $booking = null;
        try {
            $booking = $this->bookingService->findBookingByBookId($bookId, $propertyId);
        } catch (\Exception $ex){
            return $ex->getMessage();
        }

        $this->logger->error("before notify");
        $res = array();
        foreach ($this->listener_services as $listener) {
            /** @var NotifyListenerInterface $listener */

            if ($listener->interestedIn($booking, $status))
                $res[] = $listener->notify($booking, $status);

        }
        $this->logger->error("before return");
        return $res;
    }
}