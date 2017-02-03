<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 17:47
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Feature;
use AppBundle\Entity\Owner;
use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyFeature;
use AppBundle\Entity\PropertyImage;
use AppBundle\Entity\Room;
use AppBundle\Entity\UploadedImages;
use AppBundle\Exception\Beds24Exception;
use AppBundle\Form\Type\ImageType;
use AppBundle\Form\Type\PropertyImageType;
use AppBundle\Service\Beds24;
use AppBundle\Service\BookingService;
use AppBundle\Service\TVarDumper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;


class BookingController extends Controller
{
    /**
     * @Route("/admin/editBookings/{propertyId}", name="edit_bookings")
     */
    public
    function editBookingsAction(Request $request, $propertyId)
    {
        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->find($propertyId);

        return $this->render('default/admin/editBookings.html.twig', array(
            'property' => $property
        ));
    }

    /**
     * @Route("/admin/importBookings/{propertyId}", name="import_bookings")
     */
    public
    function importBookingsAction(Request $request, $propertyId) {
        /** @var BookingService $bookingService */
        $bookingService=$this->get('booking');

        /** @var Property $property */
        $property = $this->getDoctrine()->getRepository("AppBundle:Property")->find($propertyId);
        try {
            $warnings = $bookingService->importBookings($property);
            foreach($warnings as $warning) {
                $this->addFlash($warning['type'], $warning['message']);
            }
        } catch(Beds24Exception $ex) {
            $this->addFlash("error", $ex->getMessage());
        }

        return $this->redirectToRoute("edit_bookings", array("propertyId"=>$propertyId));
    }

    /**
     * @Route("/admin/importAllBookings", name="import_all_bookings")
     */
    public
    function importAllBookingsAction(Request $request) {

        /** @var Owner $user */
        $user = $this->getUser();


        if(!isset($user)) {
            $this->addFlash("error", "Please login");
            return $this->redirectToRoute("admin_main");
        }

        /** @var BookingService $bookingService */
        $bookingService=$this->get('booking');

        $properties = $user->getProperties();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPERADMIN')) {
            $properties = $this->getDoctrine()->getRepository("AppBundle:Property")->findAll();
        }

        foreach($properties as $property) {
            try {
                $warnings = $bookingService->importBookings($property);
                foreach ($warnings as $warning) {
                    $this->addFlash($warning['type'], $warning['message']);
                }
            } catch (Beds24Exception $ex) {
                $this->addFlash("error", $ex->getMessage());
            }
        }

        return $this->redirectToRoute("admin_main");
    }

}