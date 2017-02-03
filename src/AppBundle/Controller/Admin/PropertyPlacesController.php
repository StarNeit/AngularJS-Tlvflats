<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 14:24
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Place;
use AppBundle\Entity\PropertyPlace;
use AppBundle\Service\TVarDumper;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

class PropertyPlacesController extends Controller
{
    /**
     * @Route("/admin/property/{propertyId}/editPlaces", name="edit_property_places")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editPlacesAction(Request $request, $propertyId)
    {
        $property = $this->getDoctrine()->getRepository("AppBundle:Property")->find($propertyId);

        $propertyPlace = new PropertyPlace();
        $propertyPlace->setProperty($property);
        $form = $this->createFormBuilder($propertyPlace)->add("place", "entity", array(
            "class" => "AppBundle:Place",
            "choice_label" => "fullName"
        ))->add("save", "submit", array("label" => "Add place"))->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($property->getPropertyPlaces() as $existingProprtyPlace) {
                if ($existingProprtyPlace->getPlace()->getId() == $propertyPlace->getPlace()->getId()) {

                    $this->addFlash("error", $propertyPlace->getPlace()->getName() . " already marked as place for this property");

                    return $this->redirectToRoute('edit_property_places', array("propertyId" => $propertyId));
                }
            }

            foreach ($property->getPropertyPlaces() as $existingProprtyPlace) {
                if ($existingProprtyPlace->getPlace()->getType() == $propertyPlace->getPlace()->getType()) {

                    $this->addFlash("notice", "You have added place of same type you already had before. " . $existingProprtyPlace->getPlace()->getName() . ": " . $propertyPlace->getPlace()->getTypeName());

                }
            }
            $em->persist($propertyPlace);
            $em->flush();

            return $this->redirectToRoute('edit_property_places', array("propertyId" => $propertyId));
        }

        return $this->render("default/admin/places/editPropertyPlaces.html.twig", array("property" => $property, "form" => $form->createView()));
    }

    /**
     * @Route("/admin/property/{propertyId}/emptyPlaces", name="empty_property_places")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function emptyPropertyPlacesAction(Request $request, $propertyId)
    {
        $propertyPlaces = $this->getDoctrine()->getRepository("AppBundle:PropertyPlace")->findBy(array("property" => $propertyId));
        $em = $this->getDoctrine()->getEntityManager();
        foreach ($propertyPlaces as $propertyPlace) {
            $em->remove($propertyPlace);
        }
        $em->flush();

        return $this->redirectToRoute("edit_property_places", array("propertyId" => $propertyId));
    }

    /**
     * @Route("/admin/property/{propertyId}/removePlace/{propertyPlaceId}", name="remove_property_place")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removePropertyPlaceAction(Request $request, $propertyId, $propertyPlaceId)
    {
        $propertyPlace = $this->getDoctrine()->getRepository("AppBundle:PropertyPlace")->find($propertyPlaceId);
        $property = $this->getDoctrine()->getRepository("AppBundle:Property")->find($propertyId);
        if ($propertyPlace != null) {
            if ($property->getMainPlace()!=null &&
                $property->getMainPlace()->getId() == $propertyPlace->getPlace()->getId()
            ) {
                $this->addFlash("error", "Unable to remove place which is set as main for this property. Try to empty property places");
            } else {
                $this->getDoctrine()->getEntityManager()->remove($propertyPlace);
                $this->getDoctrine()->getEntityManager()->flush();
            }
        }
        return $this->redirectToRoute("edit_property_places", array("propertyId" => $propertyId));
    }
}