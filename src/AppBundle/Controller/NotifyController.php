<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 04.10.2015
 * Time: 17:18
 */

namespace AppBundle\Controller;


use AppBundle\Service\NotifyService;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotifyController extends Controller
{

    /**
     * @Route("/beds24_admin/notify.php", name="beds24_notify")
     */
    public function notify(Request $request) {

        /** @var NotifyService $notify */
        $notify = $this->get("notify");
        return new Response(print_r($notify->notify($request), true));
    }
}