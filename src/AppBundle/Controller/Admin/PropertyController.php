<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 17:40
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Feature;
use AppBundle\Entity\Owner;
use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyFeature;
use AppBundle\Entity\PropertyImage;
use AppBundle\Entity\PropertyPlace;
use AppBundle\Entity\Room;
use AppBundle\Entity\UploadedImages;
use AppBundle\Form\Type\ImageType;
use AppBundle\Form\Type\PropertyImageType;
use AppBundle\Service\Beds24;
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


class PropertyController extends Controller
{
    /**
     * @Route("/admin/property/remove/{propertyId}", name="remove_property")
     */
    public function removePropertyAction(Request $request, $propertyId)
    {
        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->find($propertyId);

        $em = $this->getDoctrine()->getManager();

        $em->remove($property);
        $em->flush();

        return $this->redirectToRoute('admin_main');
    }


    /**
     * @Route("/admin/synchronizePropertyTo/{propertyId}", name="synchronize_property_to")
     */
    public
    function synchronizePropertyToAction(Request $request, $propertyId)
    {
        return $this->redirectToRoute("edit_property", array("propertyId" => $propertyId));
    }

    /**
     * @Route("/admin/synchronizePropertyFrom/{propertyId}", name="synchronize_property_from")
     */
    public
    function synchronizePropertyFromAction(Request $request, $propertyId)
    {
        /* @var $property Property */
        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->find($propertyId);


        /* @var $beds24 Beds24 */
        $beds24 = $this->get("beds24");

        /* @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $propid = $property->getPropid();
        $propKey = $property->getPropKey();
        if (empty($propid) || empty($propKey)) {
            $this->addFlash("error", "Can not synchronize from beds24 when propId and/or propKey is not set");
            return $this->redirectToRoute("edit_property", array("propertyId" => $propertyId));
        } else {
            //merging existing
            $api_resp = $beds24->getProperty($property->getOwner()->getApiKey(), $property->getPropKey());

            if (isset($api_resp['error'])) {
                $this->addFlash("error", "Can not syncronize from beds24: " . $api_resp['error']);
                return $this->redirectToRoute("edit_property", array("propertyId" => $propertyId));
            }

            $api_property = $api_resp['getProperty'][0];

            foreach ($api_property['roomTypes'] as $roomType) {
                $room = $this->findRoom($property, $roomType['roomId']);

                if ($room == null) {
                    $room = new Room();
                    $room->setProperty($property);
                    $room->setRoomid($roomType['roomId']);
                    $room->setDisplayPrice($roomType['minPrice']);

                    $property->addRoom($room);

                    $this->addFlash("notice", "Added new room from beds24. Please check settings.");
                }

                $room->setAccomodate($roomType['maxPeople']);
                $room->setTitle($roomType['name']);

                $em->persist($room);
                $em->flush();
            }

            return $this->redirectToRoute("edit_property", array("propertyId" => $propertyId));
//            return new Response(json_encode($api_property), 200, $this->json_type);
        }

        return $this->redirectToRoute("edit_property", array("propertyId" => $propertyId));
    }

    private
    function findRoom(Property $property, $roomId)
    {
        /* @var $room Room */
        foreach ($property->getRooms() as $room) {
            if ($room->getRoomid() == $roomId) {
                return $room;
            }
        }

        return null;
    }

    /**
     * @Route("/admin/property/importProperties", name="import_properties")
     */
    public function importPropertiesAction(Request $request)
    {
        /** @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        /* @var $beds24 Beds24 */
        $beds24 = $this->get("beds24");

        $properties = $this->toMap(
            $this->getDoctrine()->getRepository("AppBundle:Property")->findAll(), function (Property $prop) {
            return $prop->getPropid();
        });

        $owners = $this->getDoctrine()->getRepository("AppBundle:Owner")->findAll();

        /* @var $owner Owner */
        $owner = $this->getUser();

        $api_properties = $beds24->getProperties($owner->getApiKey());

        if (isset($api_properties['error'])) {
            $this->addFlash("error", $api_properties['error']);
            return $this->redirectToRoute("admin_main");
        }

//        $detailed=array();

        foreach ($api_properties['getProperties'] as $api_property) {
            if (!isset($properties[$api_property['propId']])) {
                $property = new Property();
                $property->setOwner($owner);
                $property->setAddress($api_property['name']);
                $property->setTitle($api_property['name']);
                $property->setPropid($api_property['propId']);
                $property->setPropKey($api_property['propKey']);


                $beds24_property = $beds24->getProperty($owner->getApiKey(), $api_property['propKey']);
                if (isset($beds24_property['error'])) {
                    $this->addFlash("error", $beds24_property['error']);
                    continue;
                }
                $detailed = $beds24_property['getProperty'][0];

                $numrooms = 0;
                foreach ($detailed['roomTypes'] as $roomType) {
                    $numrooms++;
                    $room = new Room();
                    $room->setTitle($roomType['name']);
                    $room->setAccomodate($roomType['maxPeople']);
                    $room->setDisplayPrice($roomType['minPrice']);
                    $room->setProperty($property);
                    $room->setRoomid($roomType['roomId']);
                    $property->addRoom($room);

                    $em->persist($room);
                }

                $property->setSingleRoom($numrooms == 1);

                $em->persist($property);
                $em->flush();

                $this->addFlash("notice", "Imported property: " . $api_property['name']);
            }
        }

//        return new Response(json_encode($detailed), 200, $this->json_type);
        return $this->redirectToRoute("admin_main");
    }


    /**
     * @Route("/admin/property/{propertyId}", name="edit_property")
     */
    public
    function editPropertyAction(Request $request, $propertyId)
    {
        if ($propertyId == 'new') {
            $property = new Property();
            $property->setOwner($this->getUser());
        } else {
            $property = $this->getDoctrine()
                ->getRepository('AppBundle:Property')
                ->find($propertyId);
        }

        $form = $this->createFormBuilder($property)
            ->add('propKey', 'text', array('required' => false))
            ->add('propId', 'text', array('required' => false))
            ->add('title', 'text')
            ->add('shortDescription', 'text')
            ->add('longDescription', 'textarea')
            ->add('address', 'text')
            ->add('singleRoom', 'checkbox')
            ->add('longitude')
            ->add('latitude')
            ->add('instantBook', 'checkbox', array("required" => false))
            ->add('owner', 'entity', array(
                'class' => 'AppBundle:Owner',
                'choice_label' => 'login',
            ))->add('mainPlace', 'entity', array(
                'class' => 'AppBundle:Place',
                'choice_label' => 'fullName',
                'required' => false
            ))
            ->add('save', 'submit', array('label' => 'Save Property'))
            ->add('saveBottom', 'submit', array('label' => 'Save Property'))
            ->getForm();

//propertyFeatures:Doctrine\Common\Collections\ArrayCollection
//rooms:Doctrine\Common\Collections\ArrayCollection
//images

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $propKey = $property->getPropKey();
            if (empty($propKey)) {
                $property->setPropKey($this->generateRandomString(32));
            }

            if (!is_null($property->getMainPlace()) && $property->getPropertyPlaces()->isEmpty()) {
                $this->addFlash("notice", "Registering parent places as places for this property");
                $this->createAndSavePlace($property->getMainPlace(), $property, $em);
                foreach ($property->getMainPlace()->getAllParents() as $place) {
                    $this->createAndSavePlace($place, $property, $em);
                }
            } else {
                $this->addFlash("notice", "If you by chance changed place for this property, please check if places are correctly set. ");
            }

            $em->persist($property);
            $em->flush();

            return $this->redirectToRoute('edit_property', array("propertyId" => $property->getId()));
        }

        return $this->render('default/admin/editProperty.html.twig', array(
            'form' => $form->createView(),
            'property' => $property
        ));
    }

    private
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private
    function toMap($objects, $idfunc)
    {
        $res = array();

        foreach ($objects as $object) {
            $res[$idfunc($object)] = $object;
        }

        return $res;
    }

    /**
     * @param $mainPlace
     * @param $property
     * @param $em
     */
    public function createAndSavePlace($mainPlace, $property, $em)
    {
        $propertyPlace = new PropertyPlace();
        $propertyPlace->setPlace($mainPlace);
        $propertyPlace->setProperty($property);
        $property->addPropertyPlace($propertyPlace);
        $em->persist($propertyPlace);
    }
}