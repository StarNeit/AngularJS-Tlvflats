<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 17:46
 */

namespace AppBundle\Controller\Admin;
use AppBundle\Entity\Feature;
use AppBundle\Entity\Owner;
use AppBundle\Entity\Property;
use AppBundle\Entity\PropertyFeature;
use AppBundle\Entity\PropertyImage;
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


class ImagesController extends Controller
{
    /**
     * @Route("/admin/removeImage/{imageId}", name="remove_image")
     */
    public function removeImageAction(Request $request, $imageId)
    {
        $image = $this->getDoctrine()
            ->getRepository('AppBundle:PropertyImage')
            ->find($imageId);

        $propertyId = $image->getProperty()->getId();

        $em = $this->getDoctrine()->getManager();

        $image->removeFile();

        $em->remove($image);
        $em->flush();

        return $this->redirectToRoute('edit_images', array('propertyId' => $propertyId));
    }

    /**
     * @Route("/admin/remove_all_images", name="remove_all_images")
     */
    public function removeAllImagesAction()
    {
        /** @var Owner $user */
        $user = $this->getUser();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $properties = $this->getDoctrine()->getRepository('AppBundle:Property')->findBy(array("owner" => $user->getId()));

        /** @var Property $property */
        foreach ($properties as $property) {
            /** @var PropertyImage $image */
            foreach ($property->getImages() as $image) {
                $image->removeFiles();
                $em->remove($image);
            }
        }

        $em->flush();

        return $this->redirectToRoute('admin_main');
    }

    /**
     * @Route("/admin/check_image_files", name="check_image_files")
     */
    public function checkImageFilesAction()
    {
        /** @var Owner $user */
        $user = $this->getUser();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $properties = $this->getDoctrine()->getRepository('AppBundle:Property')->findBy(array("owner" => $user->getId()));

        $num = 0;
        /** @var Property $property */
        foreach ($properties as $property) {
            /** @var PropertyImage $image */
            foreach ($property->getImages() as $image) {
                $num++;
                if ($image->fileMissing()) {
                    $image->removeFiles();
                    $em->remove($image);
                    $this->addFlash("notice", "Image " . $image->getPath() . " was missing for property " . $property->getTitle() . ". Cleared from database");
                }
            }
        }

        $em->flush();

        $this->addFlash("notice", $num . " images verified");
        return $this->redirectToRoute('admin_main');
    }

    /**
     * @Route("/admin/regenerate_all_thumbnails", name="regenerate_all_thumbnails")
     */
    public function regenerateAllThumbnailsAction()
    {
        /** @var Owner $user */
        $user = $this->getUser();

        $properties = $this->getDoctrine()->getRepository('AppBundle:Property')->findBy(array("owner" => $user->getId()));

        /** @var Property $property */
        foreach ($properties as $property) {
            /** @var PropertyImage $image */
            foreach ($property->getImages() as $image) {
                $image->generateThumbnails(
                    $this->getParameter("image.aspect_ratio", 0.75)
                    , $this->getParameter("image.aspect_threshold", 0.15)
                    , $this->getParameter("image.small_height", 245)
                    , $this->getParameter("image.large_height", 816)
                );
            }
        }

        return $this->redirectToRoute('admin_main');
    }

    /**
     * @Route("/admin/editImages/{propertyId}", name="edit_images")
     */
    public
    function editImagesAction(Request $request, $propertyId)
    {
        /** @var Property $property */
        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->find($propertyId);

        /** @var FormFactory $ff */
        $ff = $this->get('form.factory');

        $formImages = $ff->createNamedBuilder('formImages', 'form', $property)
            ->add('images', 'collection', array(
                'type' => new PropertyImageType()
            ))
            ->add('save', 'submit', array('label' => 'Save changes'))
            ->add('save_bottom', 'submit', array('label' => 'Save changes'))
            ->add('generate_thumbnails', 'submit', array('label' => 'Generate thumbnails for all'))
            ->add('remove_all', 'submit', array('label' => 'Remove all'))
            ->getForm();
        $uploadedImages = new UploadedImages();

        $form = $this->createFormBuilder($uploadedImages)
            ->add('file', 'file', array('multiple' => true,
                'constraints' => array(
                    new All(array(
                        new Image(
                            array(
                                'maxSize' => '4M',
                            )
                        )
                    ))
                )))
            ->add('save', 'submit', array('label' => 'Upload image'))
            ->getForm();

        $form->handleRequest($request);
        $formImages->handleRequest($request);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $newImages = $uploadedImages->upload($property);

            if ($newImages != null && !empty($newImages)) {
                /** @var PropertyImage $image */
                foreach ($newImages as $image) {
                    $image->generateThumbnails(
                        $this->getParameter("image.aspect_ratio", 0.75)
                        , $this->getParameter("image.aspect_threshold", 0.15)
                        , $this->getParameter("image.small_height", 245)
                        , $this->getParameter("image.large_height", 816)
                    );
                    $em->persist($image);
                }
                $em->flush();
            } else {
                $this->addFlash("error", "Image was not uploaded");
            }

            return $this->redirectToRoute('edit_images', array('propertyId' => $propertyId));
        }

        if ($formImages->isValid()) {

            $clickedButtonName = $formImages->getClickedButton()->getName();
            if ($clickedButtonName == "generate_thumbnails") {
                /** @var PropertyImage $image */
                foreach ($property->getImages() as $image) {
                    $image->removeThumbnails();
                    $image->generateThumbnails(
                        $this->getParameter("image.aspect_ratio", 0.75)
                        , $this->getParameter("image.aspect_threshold", 0.15)
                        , $this->getParameter("image.small_height", 245)
                        , $this->getParameter("image.large_height", 816)
                    );
                }
            } elseif ($clickedButtonName == "remove_all") {

                /** @var PropertyImage $image */
                foreach ($property->getImages() as $image) {
                    $image->removeFiles();
                    $em->remove($image);

                    $em->flush();
                }
            } else {

                /** @var PropertyImage $image */
                foreach ($property->getImages() as $image) {
                    if ($image->getRemove()) {
                        $image->removeFiles();
                        $em->remove($image);
                    }

                    $em->flush();
                }
            }
            return $this->redirectToRoute('edit_images', array('propertyId' => $propertyId));
        }

        return $this->render('default/admin/editImages.html.twig', array(
            'form' => $form->createView(),
            'property' => $property,
            'formImages' => $formImages->createView()
        ));
    }

    protected function getParameter($name, $default = null)
    {
        if ($this->container->hasParameter($name)) {
            return $this->container->getParameter($name);
        }
        return $default;
    }

}