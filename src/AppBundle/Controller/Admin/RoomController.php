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
use AppBundle\Entity\RoomComponent;
use AppBundle\Entity\UploadedImages;
use AppBundle\Form\Type\ComponentType;
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


class RoomController extends Controller
{
    /**
     * @Route("/admin/editRooms/{propertyId}", name="edit_rooms")
     */
    public
    function editRoomsAction(Request $request, $propertyId)
    {
        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->find($propertyId);

        return $this->render('default/admin/editRooms.html.twig', array(
            'property' => $property
        ));
    }

    /**
     * @Route("/admin/editRoom/{propertyId}/{roomId}", name="edit_room")
     */
    public
    function editRoomAction(Request $request, $propertyId, $roomId)
    {
        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->find($propertyId);

        if ($roomId == 'newRoom') {
            $room = new Room();
            $room->setProperty($property);
        } else {
            $room = $this->getDoctrine()
                ->getRepository('AppBundle:Room')
                ->find($roomId);
        }

        $form = $this->createFormBuilder($room)
            ->add('title', 'text')
            ->add('displayPrice', 'money')
            ->add('basePrice', 'money')
            ->add('roomsAvailable', "integer")
            ->add('accomodate', 'integer')
            ->add('bedrooms', 'integer')
            ->add('bathrooms', 'integer')
            ->add('size', 'number')
            ->add('floor', 'integer')
            ->add('balkony', "integer")
            ->add('deposit', 'money')
            ->add('cleaningFee', 'money')
            ->add('save', 'submit', array('label' => 'Save Room'))
            ->add('saveTop', 'submit', array('label' => 'Save Room'))
            ->getForm();

        /** @var FormFactory $ff */
        $ff = $this->get('form.factory');

        $componentsForm = $ff->createNamedBuilder('formComponents', 'form', $room, array('csrf_protection' => false))
            ->add('components', 'collection', array(
                'type' => new ComponentType(),
                'allow_add' => true,
                'allow_delete' => true,
                'prototype_name' => '__room__name__'
            ))
            ->add('add_room', 'button', array('label' => 'Add room'))
            ->add('save', 'submit', array('label' => 'Save changes'))
            ->getForm();
        

        $form->handleRequest($request);
        $componentsForm->handleRequest($request);
        
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($room);
            $em->flush();

            return $this->redirectToRoute('edit_rooms', array('propertyId' => $propertyId));
        }
        
        if($componentsForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //remove empty amenities
            /** @var RoomComponent $component */
            foreach ($room->getComponents() as $component) {
                if($component->getName() == '') {
                    $em->remove($component);
                    $room->getComponents()->removeElement($component);
                } else {
                    $amenities = $component->getAmenities();
                    $amenities = array_values(array_filter($amenities));
                    $component->setAmenities($amenities);
                    $component->setRoom($room);
                }
            }

            $em->persist($room);
            $em->flush();

            return $this->redirectToRoute('edit_room', array('propertyId' => $propertyId, 'roomId'=>$roomId));
        }

        return $this->render('default/admin/editRoom.html.twig', array(
            'form' => $form->createView(),
            'componentsForm' => $componentsForm->createView(),
            'property' => $property
        ));
    }

    /**
     * @Route("/admin/removeRoom/{propertyId}/{roomId}", name="remove_room")
     */
    public
    function removeRoomAction(Request $request, $propertyId, $roomId)
    {
        $room = $this->getDoctrine()
            ->getRepository('AppBundle:Room')
            ->find($roomId);

        $em = $this->getDoctrine()->getManager();

        $em->remove($room);
        $em->flush();


        return $this->redirectToRoute('edit_property', array('propertyId' => $propertyId));
    }


}