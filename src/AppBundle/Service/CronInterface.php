<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 30/05/16
 * Time: 12:05
 */

namespace AppBundle\Service;


interface CronInterface
{
    /** @return string */
    public function spec();
    
    public function run();
}