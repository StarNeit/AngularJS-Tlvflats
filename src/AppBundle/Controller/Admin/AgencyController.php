<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 09/03/16
 * Time: 17:12
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Agency;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AgencyController extends Controller
{
    /**
     * @Route("/admin/agency/{agencyId}", name="edit_agency")
     */
    public function editAgencyAction(Request $request, $agencyId)
    {
        if ($agencyId == 'new') {
            $agency = new Agency();
            $agency->setDateJoined(new \DateTime());
        } else {
            $agency = $this->getDoctrine()
                ->getRepository('AppBundle:Agency')
                ->find($agencyId);
        }

        $form = $this->createFormBuilder($agency)
            ->add('name', 'text')
            ->add('dateEstablished', 'date', array("required"=>false))
            ->add('dateJoined', 'date')
            ->add('save', 'submit', array('label' => 'Save Agency'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($agency);
            $em->flush();

            return $this->redirectToRoute('edit_agency', array("agencyId"=>$agency->getId()));
        }

        return $this->render('default/admin/editAgency.html.twig', array(
            'form' => $form->createView()
        ));


    }

    /**
     * @Route("/admin/agency/{agencyId}/remove", name="remove_agency")
     */
    public function removeAgencyAction(Request $request, $agencyId)
    {
        $this->addFlash("admin", "Can not remove agency");
        return $this->redirectToRoute("admin_main");
    }
}