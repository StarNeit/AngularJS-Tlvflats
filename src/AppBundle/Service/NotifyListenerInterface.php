<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 04.10.2015
 * Time: 17:57
 */

namespace AppBundle\Service;


use AppBundle\Entity\Booking;

interface NotifyListenerInterface
{

    public function notify(Booking $booking, $status);

    public function interestedIn(Booking $booking, $status);
}