<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 09/03/16
 * Time: 17:12
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Agency;
use AppBundle\Entity\Channel;
use AppBundle\Entity\Currency;
use AppBundle\Service\ChannelInterface;
use AppBundle\Service\ChannelServiceInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChannelController extends Controller
{
    /**
     * @Route("/admin/channel/{channelId}", name="edit_channel")
     */
    public function editChannelAction(Request $request, $channelId)
    {
        if ($channelId == 'new') {
            $channel = new Channel();
        } else {
            $channel = $this->getDoctrine()
                ->getRepository('AppBundle:Channel')
                ->find($channelId);
        }

        $form = $this->createFormBuilder($channel)
            ->add('name', 'text')
            ->add('serviceName', 'text')
            ->add('save', 'submit', array('label' => 'Save channel'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($channel);
            $em->flush();


            if(!$this->container->has($channel->getServiceName()) || !($this->get($channel->getServiceName()) instanceof ChannelServiceInterface)) {
                $this->addFlash("error", "Service with ".$channel->getServiceName()." not found or of wrong interface. Channel saved, but won't be working correctly");
                return $this->redirectToRoute("edit_channel", array("channelId"=>$channel->getId()));
            }


            $url = $this->generateUrl("admin_main");

            return $this->redirect($url.'#channels');
        }

        return $this->render('default/admin/editChannel.html.twig', array(
            'form' => $form->createView()
        ));


    }

    /**
     * @Route("/admin/channel/{channelId}/remove", name="remove_channel")
     */
    public function removeChannelAction(Request $request, $channelId)
    {
        /** @var Channel $channel */
        $channel = $this->getDoctrine()->getRepository("AppBundle:Channel")->find($channelId);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getEntityManager();

        $em->remove($channel);
        $em->flush();

        $url = $this->generateUrl("admin_main");

        return $this->redirect($url.'#channels');
    }
}