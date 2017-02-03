<?php

namespace AppBundle\Controller\Admin\Prices;

use AppBundle\Entity\RateScope;
use AppBundle\Entity\RateScopeSet;
use AppBundle\Form\Type\RateScopeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RateScopeController extends Controller
{
    /**
     * @param $ownerId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/rate/scopes/{ownerId}", name="index_rate_scopes", defaults={"ownerId"=NULL})
     */
    public function indexAction($ownerId = NULL)
    {
        $rateScopeSets = $this->getDoctrine()->getRepository("AppBundle:RateScopeSet")->findBy(array(
            "owner" => $ownerId
        ));
        return $this->render('default/admin/prices/editScopes.html.twig', array('rateScopeSets' => $rateScopeSets));
    }

    /**
     * @param $rateScopeSetId
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/admin/rate/scopeset/{rateScopeSetId}", name="edit_rate_scope_set")
     */
    public function editRateScopeSetAction(Request $request, $rateScopeSetId)
    {
        if ($rateScopeSetId === 'new') {
            $rateScopeSet = new RateScopeSet();
        } else {
            $rateScopeSet = $this->getDoctrine()->getRepository("AppBundle:RateScopeSet")->find($rateScopeSetId);
        }

        $builder = $this->createFormBuilder($rateScopeSet);
        $form = $builder
            ->add("name", "text")
            ->add("rateScopes", "collection", array(
                "allow_add" => true,
                "allow_delete" => true,
                "prototype" => true,
                "type" => new RateScopeType()
            ))
            ->add("save", "submit")
            ->getForm();

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {

            if ($form->getClickedButton()->getName() === 'save') {

                /** @var RateScope $rateScope */
                foreach ($rateScopeSet->getRateScopes() as $rateScope) {
                    $rateScope->setRateScopeSet($rateScopeSet);
                }

                $em->persist($rateScopeSet);
            } else {
                /** @var RateScope $rateScope */
                $rateScope = $form->getClickedButton()->getParent()->getData();
                $em->remove($rateScope);
            }
            $em->flush();
            return $this->redirectToRoute("edit_rate_scope_set", array("rateScopeSetId" => $rateScopeSet->getId()));
        }

        return $this->render('default/admin/prices/editScopeSet.html.twig', array(
            'rateScopeSet' => $rateScopeSet,
            'form' => $form->createView()
        ));
    }

}
