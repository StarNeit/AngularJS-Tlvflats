<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 12.07.2015
 * Time: 18:18
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyRepository;
use AppBundle\Service\Beds24;
use AppBundle\Service\BookingService;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApartmentController extends FOSRestController
{
    /**
     * @Get("/api/property/list")
     * @RestView
     *
     */
    public function listAllAction(Request $request)
    {
        $beds24 = $this->get("beds24");

        $dateFrom = $request->query->get("dateFrom");
        $dateTo = $request->query->get("dateTo");
        $visitors = $request->query->get("visitors", 0);
        $searchTerm = $request->query->get("searchTerm");
        $place = $request->query->get("place");

        /** @var PropertyRepository $propertyRepository */
        $propertyRepository = $this->getDoctrine()
            ->getRepository('AppBundle:Property');
        $properties = $propertyRepository
//            ->findAllWithProperties($visitors, $searchTerm, $place, null, $dateTo);
            ->findAllWithProperties($visitors, $searchTerm, $place, $dateFrom, $dateTo);

        $rooms = array();
        /** @var Property $property */
        foreach ($properties as $property) {
            foreach ($property->getRooms() as $room) {
                array_push($rooms, $room->getRoomId());
            }
        }

//        if (!empty($dateFrom) && !empty($dateTo)) {
//            $availability = $beds24->getAvailability($rooms, $dateFrom, $dateTo, $visitors);
//
//            foreach ($properties as $propId => $property) {
//                try {
//                    foreach ($property->getRooms() as $roomId => $room) {
//                        if (!isset($availability[$room->getRoomId()]) || !$availability[$room->getRoomId()][$room->getRoomId()]['roomsavail']) {
//                            unset($property->getRooms()[$roomId]);
//                        }
//                    }
//                    if (count($property->getRooms()) == 0) {
//                        unset($properties[$propId]);
//                    }
//                } catch(\Exception $ex) {
//                    //ignore it
//                }
//            }
//        }

        return array_values($properties);
    }

    /**
     * @Get("/api/availability/{propertyId}")
     * @RestView(serializerGroups={"availability"})
     *
     */
    public function availabilityAction(Request $request, $propertyId) {
        $startOfMonth = (new \DateTime(date('Y-m-01')));
        $endOfMonth = (new \DateTime(date('Y-m-t')));

        $dateFrom = $request->query->get("dateFrom", $startOfMonth);
        $dateTo = $request->query->get("dateTo", $endOfMonth);

        if(!($dateFrom instanceof \DateTime)) $dateFrom = new \DateTime($dateFrom);
        if(!($dateTo instanceof \DateTime)) $dateTo = new \DateTime($dateTo);

        /** @var BookingService $bookingService */
        $bookingService = $this->get("booking");
        
        $bookings = $bookingService->findBookingsInRange($propertyId, $dateFrom, $dateTo);
        
        return $bookings;
    }

    /**
     * @Get("/api/price/{propertyId}")
     * @RestView()
     *
     */
    public function priceAction(Request $request, $propertyId) {
        $dateFrom = $request->query->get("dateFrom");
        $dateTo = $request->query->get("dateTo");
        $visitors = $request->query->get("visitors");

        if(empty($dateFrom) || empty($dateTo) || empty($visitors)) {
            throw new NotFoundHttpException("Should specify date from and date to");
        }

        if(!($dateFrom instanceof \DateTime)) $dateFrom = new \DateTime($dateFrom);
        if(!($dateTo instanceof \DateTime)) $dateTo = new \DateTime($dateTo);

        $visitors = intval($visitors);
        
        /** @var Beds24 $bed24 */
        $bed24 = $this->get("beds24");

        $property = $this->getDoctrine()->getRepository("AppBundle:Property")->find($propertyId);

        $price = $bed24->getPrice($property, $dateFrom, $dateTo, $visitors);

        if($price['roomsavail']>0) {
            $price['priceItems']['cleaningFee'] = $property->getRooms()->get(0)->getCleaningFee();
            $price['deposit'] = $property->getRooms()->get(0)->getDeposit();
        }
        
        return $price;
    }

    /**
     * @Get("/api/property/{id}")
     * @RestView
     *
     */
    public function getIdAction($id)
    {

        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->findOneWithProperties($id);
        if ($property) {
            return $property;
        } else {
            throw new NotFoundHttpException("Apartament not found");
        }
    }
}