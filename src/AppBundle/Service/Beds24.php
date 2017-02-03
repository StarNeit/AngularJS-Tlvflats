<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 22.07.2015
 * Time: 15:09
 */

namespace AppBundle\Service;


use AppBundle\Entity\Property;
use AppBundle\Exception\Beds24Exception;
use Monolog\Logger;

class Beds24
{
    protected $pest;
    /**
     * @var Logger
     */
    private $logger;

    function __construct(Logger $logger)
    {
        $this->pest = new \PestJSON("http://api.beds24.com");
        $this->logger = $logger;
    }

    function getAvailability($roomIds, $dateFrom, $dateTo, $visitorsCount)
    {
        $res = array();
        foreach ($roomIds as $roomId) {
            $timestampFrom = strtotime($dateFrom);
            $timestampTo = strtotime($dateTo);

            $res[$roomId]= $this->pest->post("/json/getAvailabilities", array(
                    "checkIn" => date('Ymd', $timestampFrom),
                    "checkOut" => date('Ymd', $timestampTo),
                    "roomId" => $roomId,
                    "numAdult" => $visitorsCount,
                ));
        }
        return $res;
    }

    public function getProperty($apiKey, $propKey)
    {
        return $this->pest->post("/json/getProperty", array(
            "authentication"=>array(
                "apiKey"=>$apiKey,
                "propKey"=>$propKey
            )
        ));
    }

    public function getProperties($apiKey)
    {
        return $this->pest->post("/json/getProperties", array(
            "authentication"=>array(
                "apiKey"=>$apiKey
            )
        ));
    }

    public function getBooking($bookId, $apiKey, $propKey)
    {
        return $this->pest->post("/json/getBookings", array(
            "authentication"=>array(
                "apiKey"=>$apiKey,
                "propKey"=>$propKey
            ),
            "bookId" => $bookId
        ));
    }

    public function findBooking($bookId, $property, $propApiKeyPairs)
    {

        //property known, let's get booking
        if(isset($property)) {
            $beds24_res = $this->getBooking($bookId, $property->getOwner()->getApiKey(), $property->getPropKey());
            if(!isset($beds24_res['error']) && count($beds24_res)==1) {
                return $beds24_res;
            }
        }

        //unknown will try to find
        foreach($propApiKeyPairs as $propKey=>$apiKey) {
            $beds24_res = $this->getBooking($bookId, $apiKey, $propKey);
            $this->logger->addInfo("Api: $apiKey, prop: $propKey");
            $this->logger->addInfo(print_r($beds24_res, true));
            if(!isset($beds24_res['error']) && count($beds24_res)==1) {
                return $beds24_res;
            }
        }

        return null;
    }

    public function setBooking($modified_booking)
    {
        return $this->pest->post("/json/setBooking", $modified_booking);
    }

    /**
     * @param $apiKey
     * @param $propKey
     * @return array
     * @throws Beds24Exception
     */
    public function getBookings($apiKey, $propKey)
    {
        $resp = $this->pest->post("/json/getBookings", array(
            "authentication" => array(
                "apiKey" => $apiKey,
                "propKey" => $propKey
            )
        ));

        if(isset($resp['error'])) {
            throw new Beds24Exception($resp['error']);
        }
        return $resp;
    }

    public function getPrice(Property $property, \DateTime $dateFrom, \DateTime $dateTo, $visitors)
    {


        $resp = $this->pest->post("/json/getAvailabilities", array(
            "authentication" => array(
                "apiKey" => $property->getOwner()->getApiKey(),
                "propKey" => $property->getPropKey()
            ),
            "roomId" => $property->getRooms()->get(0)->getRoomId(),
            "checkIn" => $dateFrom->format("Ymd"),
            "checkOut" => $dateTo->format("Ymd"),
            "numAdult" => $visitors
        ));

        if(isset($resp['error'])) {
            throw new Beds24Exception($resp['error']);
        }

        $price = $resp[$property->getRooms()->get(0)->getRoomId()];
        unset($price["roomId"]);
        unset($price["propId"]);
        unset($price["priceFormated"]);
        $roomsavail = $price["roomsavail"];
        unset($price["roomsavail"]);
        return array("roomsavail"=>$roomsavail,"priceItems"=>$price);
    }


}