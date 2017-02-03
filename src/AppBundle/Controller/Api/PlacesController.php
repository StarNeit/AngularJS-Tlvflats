<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 16:35
 */

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Place;
use AppBundle\Entity\PlaceRepository;
use AppBundle\Service\Beds24;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View as RestView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PlacesController extends FOSRestController
{
    /**
     * @Get("/api/places/suggest")
     * @RestView
     *
     */
    public function placesSuggestAction(Request $request)
    {
        $q = $request->query->get("q", "");
        $maxType = $request->query->get("maxType", Place::DISTRICT);
        if (strlen($q) < 3) {
            return array();
        }

        /** @var PlaceRepository $repository */
        $repository = $this->getDoctrine()->getRepository("AppBundle:Place");
        $places = $repository->findSuggestions($q, $maxType);

        $places = $this->expandChildren($places, $maxType);

        return $places;
    }

    /**
     * @Get("/api/places/list")
     * @RestView
     *
     */
    public function placesListAction()
    {
        $places = $this->getDoctrine()->getRepository("AppBundle:Place")->findAll();

        return $places;
    }

    private function expandChildren($places, $maxType)
    {
        $res = array();
        foreach ($places as $place) {
            if($place->getType()>$maxType) return $res;
            array_push($res, $place);
            $res = array_merge($res, $this->expandChildren($place->getChildren(), $maxType));
        }

        return $res;
    }
}