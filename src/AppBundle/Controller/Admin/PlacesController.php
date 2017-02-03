<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 14:24
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Place;
use AppBundle\Service\TVarDumper;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

class PlacesController extends Controller
{
    /**
     * @Route("/admin/editPlaces/{placeId}", defaults={"placeId"=null}, name="edit_places")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editPlacesAction(Request $request, $placeId = null)
    {
        $listPlaces = $this->getDoctrine()->getRepository("AppBundle:Place")->findAll();
        $map = $this->convertToMap($listPlaces);
        $toRender = $this->buildTwigData($map);

        $place = new Place();
        if ($placeId != null && isset($map[$placeId])) {
            $place = $map[$placeId];
        }

        $form = $this->createFormBuilder($place)
            ->add('name', 'text')
            ->add('type', 'choice', array("choices" => array(
                "Country" => Place::COUNTRY,
                "Region" => Place::REGION,
                "City" => Place::CITY,
                "District" => Place::DISTRICT
            ), 'choices_as_values' => true))
            ->add("parent", 'entity', array(
                'class' => 'AppBundle:Place',
                'choice_label' => 'name',
                'required' => false
            ))
            ->add('searchable', 'checkbox', array('required' => false))
            ->add('save', 'submit', array('label' => 'Save place'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($place == $place->getParent()) {
                $this->addFlash("error", "Place can not be part of itself");
                return $this->redirectToRoute('edit_places');
            }

            $em->persist($place);
            $em->flush();

            return $this->redirectToRoute('edit_places');
        }


        return $this->render("default/admin/places/editPlaces.html.twig", [
            "data" => $toRender,
            "json" => json_encode($toRender),
            "form" => $form->createView()
        ]);
    }

    private function convertToMap($listPlaces)
    {
        $res = array();
        /** @var Place $place */
        foreach ($listPlaces as $place) {
            $res[$place->getId()] = $place;
        }

        return $res;
    }

    private function buildTwigData($map)
    {
        $res = array();

        /**
         * @var Place $v
         */
        foreach ($map as $k => $v) {
            $fullName = $v->getFullName();
//            $fullName = $this->buildFullName($v, $map);
            array_push($res, array(
                "id" => $v->getId(),
                "parent_id" => $v->getParentId(),
                "searchable" => $v->getSearchable(),
                "name" => $v->getName(),
                "type" => $v->getType(),
                "full_name" => $fullName
            ));
        }

        return $res;
    }

    private function buildFullName(Place $v, $map)
    {
        $iter = 0;
        $fullName = $v->getName();
        /** @var Logger $log */
        $log = $this->get('logger');
        if (is_null($v->getParentId())) {
            return $fullName;
        }
        /** @var Place $current */
        $current = $map[$v->getParentId()];
        while ($iter < 10) {

            $fullName = $fullName . ", " . $current->getName();

            if (is_null($current->getParentId())) {
                break;
            }

            $current = $map[$current->getParentId()];
            $iter++;
        }
        if ($iter >= 10) {
            $log->addWarning("Suspicious depth of place name");
        }

        return $fullName;
    }

    /**
     * @Route("/admin/removePlace/{placeId}", name="remove_place")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function removePlaceAction($placeId)
    {
        /** @var Place $place */
        $place = $this->getDoctrine()->getRepository("AppBundle:Place")->find($placeId);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getEntityManager();

        if ($place == null) {
            $this->addFlash("error", "Unable to remove place $placeId. Already removed");
            return $this->redirectToRoute("edit_places");
        }

        $em->beginTransaction();
        $parent = $place->getParent();

        /** @var Place $child */
        foreach ($place->getChildren() as $child) {
            $child->setParent($parent);
//            $em->flush($child);
        }
        $em->remove($place);
        $em->flush();
        $em->commit();

        return $this->redirectToRoute("edit_places");
    }
}