<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 28.07.2015
 * Time: 22:21
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

/**
 * Class AdminController
 * @package AppBundle\Controller
 */
class AdminController extends Controller
{
    private $json_type = array("Content-type" => "application/json");

    /**
     * @Route("/admin", name="admin_main")
     * @Route("/admin/", name="admin_main")
     */
    public function adminAction()
    {
        /* @var $user Owner */
        $user = $this->getUser();

        $properties = $this->getDoctrine()
            ->getRepository('AppBundle:Property')
            ->findBy(array("owner" => $user->getId()));

        $users = $this->getDoctrine()
            ->getRepository('AppBundle:Owner')
            ->findAll();

        $features = $this->getDoctrine()
            ->getRepository('AppBundle:Feature')
            ->findAll();

        $agencies = $this->getDoctrine()
            ->getRepository("AppBundle:Agency")
            ->findAll();

        $currencies = $this->getDoctrine()
            ->getRepository("AppBundle:Currency")
            ->findAll();

        $channels = $this->getDoctrine()
            ->getRepository("AppBundle:Channel")
            ->findAll();

        return $this->render('default/admin/index.html.twig', array(
            "properties" => $properties,
            "users" => $users,
            "features" => $features,
            "agencies" => $agencies,
            "currencies" => $currencies,
            "channels" => $channels
        ));
    }
}