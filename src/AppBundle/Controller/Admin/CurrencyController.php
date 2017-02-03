<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 09/03/16
 * Time: 17:12
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Agency;
use AppBundle\Entity\Currency;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends Controller
{
    /**
     * @Route("/admin/currency/{currencyId}", name="edit_currency")
     */
    public function editCurrencyAction(Request $request, $currencyId)
    {
        if ($currencyId == 'new') {
            $currency = new Currency();
        } else {
            $currency = $this->getDoctrine()
                ->getRepository('AppBundle:Currency')
                ->find($currencyId);
        }

        $form = $this->createFormBuilder($currency)
            ->add('name', 'text')
            ->add('code', 'text')
            ->add('symbol', 'text')
            ->add('before', 'checkbox', array("required"=>false))
            ->add('save', 'submit', array('label' => 'Save Currency'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($currency);
            $em->flush();

            $url = $this->generateUrl("admin_main");

            return $this->redirect($url.'#currencies');
        }

        return $this->render('default/admin/prices/editCurrency.html.twig', array(
            'form' => $form->createView()
        ));


    }

    /**
     * @Route("/admin/currency/{currencyId}/remove", name="remove_currency")
     */
    public function removeCurrencyAction(Request $request, $currencyId)
    {
        $this->addFlash("error", "Can not remove curency");
        return $this->redirectToRoute("admin_main");
    }
}