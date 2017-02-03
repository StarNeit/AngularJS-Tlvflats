<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 17:45
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\EmailAccount;
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
use AppBundle\Service\ImapService;
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


class UserController extends Controller
{
    /**
     * @Route("/admin/user/{userId}/{emailId}", name="edit_user", defaults={"emailId"=null}, requirements={
     *       "userId": "(new|\d+)",
     *       "emailId": "\d+"
     *     })
     */
    public function editUserAction(Request $request, $userId, $emailId = null)
    {
        /** @var ImapService $imap */
        $imap = $this->get("imap");
        if ($userId == 'new') {
            $user = new Owner();
        } else {
            $user = $this->getDoctrine()
                ->getRepository('AppBundle:Owner')
                ->find($userId);
        }

        $email = null;
        if (isset($emailId)) {
            /** @var EmailAccount $cemail */
            foreach ($user->getEmails() as $cemail) {
                if ($emailId == $cemail->getId()) {
                    $email = $cemail;
                    break;
                }
            }
        }

        if (!isset($email)) {
            $email = new EmailAccount();
            $email->setOwner($user);
        } else {
            if (!empty($request->get('invalid')))
                $email->setValid("INVALID");
            else
                $email->setValid("UNCHECKED");
            $email_pass_before = $email->getServerPassword();
        }

        if (!empty($request->get('checkEmails'))) {
            /** @var EmailAccount $cemail */
            foreach ($user->getEmails() as $cemail) {
                try {
                    $imapCon = $imap->openImap($cemail);
                    $imap->close($imapCon);
                    $cemail->setValid("VALID");
                } catch (\Exception $ex) {
                    $cemail->setValid("INVALID: " . $ex->getMessage());
                }
            }
        }

        $form = $this->createFormBuilder($user)
            ->add('apiKey', 'text')
            ->add('login', 'text')
            ->add('passHash', 'password', array('required' => false))
            ->add('admin', 'checkbox', array('required' => false))
            ->add('save', 'submit', array('label' => 'Save user'))
            ->getForm();

        /** @var FormFactory $ff */
        $ff = $this->get('form.factory');

        $emailForm = $ff->createNamedBuilder('email', 'form', $email)
            ->add("email", "email")
            ->add("serverHost", "text")
            ->add("serverPort", "integer")
            ->add("serverLogin", "text")
            ->add("serverPassword", "password", array('required' => false))
            ->add("checkOnCreate", "checkbox", array('required' => false))
            ->add('save', 'submit', array('label' => 'Save email'))
            ->add('remove', 'submit', array('label' => 'Remove email'))
            ->getForm();


        $hash_before = $user->getPassHash();
        $admin_before = $user->getAdmin();

        $form->handleRequest($request);
        $emailForm->handleRequest($request);

        /* @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();
        if ($form->isValid()) {

            if ($userId != 'new') {
                if ($hash_before == $user->getPassHash() || empty($user->getPassHash())) {
                    //set back right value
                    $user->setPassHash($hash_before);
                } else {
                    $user->setPassHash(hash('sha512', $user->getPassHash()));
                }
                if ($admin_before != $user->getAdmin()) {
                    if (!$user->getAdmin() && $userId == $this->getUser()->getId()) {
                        $this->addFlash("error", "Can not deny admin rights from myself");
                        $user->setAdmin(true);
                    }
                }
            } else {
                $user->setPassHash(hash('sha512', $user->getPassHash()));
            }

            $em->persist($user);
            $em->flush();

//            return new Response(json_encode($newChanges), 200, $this->json_type);
            $this->addFlash("notice", "User " . $user->getLogin() . " modified/created");
            return $this->redirectToRoute('edit_user', array("userId" => $user->getId()));
        }

        if ($emailForm->isValid()) {
            if ($emailForm->getClickedButton()->getName() == 'remove') {
                $em->remove($email);
                $em->flush();
            } else {
                $email->setOwner($user);
                if (empty($email->getServerPassword()) && isset($email_pass_before)) {
                    $email->setServerPassword($email_pass_before);
                }
                $em->persist($email);
                $em->flush();
                if ($email->getCheckOnCreate()) {
                    $failed = false;
                    try {
                        $imapConnection = $imap->openImap($email);
                        $imap->close($imapConnection);
                    } catch (\Exception $ex) {
                        $this->addFlash("error", "Was not able to connect with specified credentials: " . $ex->getMessage());
                        $failed = true;
                    }
                    if ($failed)
                        return $this->redirectToRoute('edit_user', array("userId" => $user->getId(), "emailId" => $email->getId(), 'invalid' => 'invalid'));
                    else
                        return $this->redirectToRoute('edit_user', array("userId" => $user->getId()));
                }
            }
            return $this->redirectToRoute('edit_user', array("userId" => $user->getId()));
        }

        return $this->render('default/admin/editUser.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'emailForm' => $emailForm->createView()
        ));
    }

    /**
     * @Route("/admin/user/remove/{userId}", name="remove_user")
     */
    public
    function removeUserAction($userId)
    {
        /* @var $user Owner */
        $user = $this->getUser();

        if ($userId == $user->getId()) {
            $this->addFlash("error", "Can not remove myself");
            return $this->redirectToRoute("admin_main");
        }

        /* @var $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        /* @var $entity Owner */
        $entity = $this->getDoctrine()->getRepository("AppBundle:Owner")->find($userId);
        if (empty($entity)) {
            $this->addFlash("error", "User with id " . $userId . " is not found");
            return $this->redirectToRoute("admin_main");
        }
        $em->remove($entity);
        $em->flush();

        $this->addFlash("notice", "User " . $entity->getLogin() . " removed with all owned properties");

        return $this->redirectToRoute("admin_main");
    }

}