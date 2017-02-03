<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 17:44
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


class FeatureController extends Controller
{
    /**
     * @Route("/admin/feature/{featureId}", name="edit_feature")
     */
    public function editFeatureAction(Request $request, $featureId)
    {
        if ($featureId == 'new') {
            $feature = new Feature();
        } else {
            $feature = $this->getDoctrine()
                ->getRepository('AppBundle:Feature')
                ->find($featureId);
        }

        $form = $this->createFormBuilder($feature)
            ->add('faName', 'text')
            ->add('name', 'text')
            ->add('save', 'submit', array('label' => 'Save Feature'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($feature);
            $em->flush();

            return $this->redirectToRoute('admin_main');
        }

        $raw_css = file_get_contents("http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css");
        $matches = array();
        preg_match_all('/\.(fa-([a-z-]+?)):before/', $raw_css, $matches);
        $features = $matches[1];

        return $this->render('default/admin/editFeature.html.twig', array(
            'form' => $form->createView(),
            'features' => $features
        ));

    }

    /**
     * @Route("/admin/feature/remove/{featureId}", name="remove_feature")
     */
    public function removeFeatureAction(Request $request, $featureId)
    {
        /* @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        /* @var $entity Feature */
        $entity = $this->getDoctrine()->getRepository("AppBundle:Feature")->find($featureId);
        if (empty($entity)) {
            $this->addFlash("error", "feature with id " . $featureId . " is not found");
            return $this->redirectToRoute("admin_main");
        }
        $em->remove($entity);
        $em->flush();

        $this->addFlash("notice", "Feature " . $entity->getName() . " removed");

        return $this->redirectToRoute("admin_main");

    }
    /**
     * @Route("/admin/editFeatures/{propertyId}", name="edit_features")
     */
    public
    function editFeaturesAction(Request $request, $propertyId)
    {
        /* @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        /* @var $property Property */
        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->find($propertyId);

        $features = $this->getDoctrine()->getRepository("AppBundle:Feature")->findAll();

        $submit = $request->get("Submit");
        if (!empty($submit)) {
            foreach ($property->getPropertyFeatures() as $pf) {
                $em->remove($pf);
            }
            foreach ($features as $feature) {
                if ($request->get("checked" . $feature->getId())) {
                    $propertyFeature = new PropertyFeature();
                    $propertyFeature->setFeature($feature);
                    $propertyFeature->setProperty($property);
                    $propertyFeature->setAttribure("");
                    $property->addPropertyFeature($propertyFeature);
                    $em->persist($propertyFeature);
                }
            }
            $em->flush();

            return $this->redirectToRoute("edit_property", array("propertyId"=>$propertyId));
        }

        $property = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->find($propertyId);

        $checked = array();
        foreach ($property->getPropertyFeatures() as $pf) {
            $checked[$pf->getFeature()->getId()] = 'checked';
        }

        return $this->render('default/admin/editFeatures.html.twig', array(
            'property' => $property,
            'propertyFeatures' => $checked,
            'features' => $features
        ));
    }


}