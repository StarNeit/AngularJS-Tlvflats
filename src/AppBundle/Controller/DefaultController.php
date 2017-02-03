<?php

namespace AppBundle\Controller;

use Monolog\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        /** @var FileLocatorInterface $locator */
        $locator = $this->get('file_locator');

        return new Response(file_get_contents($locator->locate("index.html")));

//        return $this->render('default/index.html');
    }
}
