<?php

namespace AppBundle\Controller\Admin\Prices;

use AppBundle\Entity\RateStepping;
use AppBundle\Entity\RateSteppingSet;
use AppBundle\Form\Type\RateSteppingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RateSteppingController extends Controller
{
    /**
     * @param $ownerId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/rate/steppings/{ownerId}", name="index_rate_steppings", defaults={"ownerId"=NULL})
     */
    public function indexAction($ownerId = NULL)
    {
        $rateSteppingSets = $this->getDoctrine()->getRepository("AppBundle:RateSteppingSet")->findBy(array(
            "owner" => $ownerId
        ));
        return $this->render('default/admin/prices/editSteppings.html.twig', array('rateSteppingSets' => $rateSteppingSets));
    }

    /**
     * @param $rateSteppingSetId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/rate/steppingset/{rateSteppingSetId}", name="edit_rate_stepping_set")
     */
    public function editRateSteppingSetAction(Request $request, $rateSteppingSetId) {
        if ($rateSteppingSetId === 'new') {
            $rateSteppingSet = new RateSteppingSet();
        } else {
            $rateSteppingSet = $this->getDoctrine()->getRepository("AppBundle:RateSteppingSet")->find($rateSteppingSetId);
        }

        $builder = $this->createFormBuilder($rateSteppingSet);
        $form = $builder
            ->add("name", "text")
            ->add("rateSteppings", "collection", array(
                "allow_add" => true,
                "allow_delete" => true,
                "prototype" => true,
                "type" => new RateSteppingType()
            ))
            ->add("save", "submit")
            ->getForm();

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {

            if ($form->getClickedButton()->getName() === 'save') {

                /** @var RateStepping $rateStepping */
                foreach ($rateSteppingSet->getRateSteppings() as $rateStepping) {
                    $rateStepping->setRateSteppingSet($rateSteppingSet);
                }

                $em->persist($rateSteppingSet);
            } else {
                /** @var RateStepping $rateStepping */
                $rateStepping = $form->getClickedButton()->getParent()->getData();
                $em->remove($rateStepping);
            }
            $em->flush();
            return $this->redirectToRoute("edit_rate_stepping_set", array("rateSteppingSetId" => $rateSteppingSet->getId()));
        }

        return $this->render('default/admin/prices/editSteppingSet.html.twig', array(
            'rateSteppingSet' => $rateSteppingSet,
            'form' => $form->createView()
        ));
        
    }}
