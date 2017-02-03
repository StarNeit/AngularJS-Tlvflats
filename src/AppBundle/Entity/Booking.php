<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 04.10.2015
 * Time: 18:02
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="booking")
 */
class Booking
{
    const CANCELLED = 0;
    const CONFIRMED = 1;
    const NEWBOOKING = 2;
    const REQUEST = 3;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="bookings")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     */
    protected $room;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $bookId;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $unitId;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $roomQty;
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Groups({"availability"})
     */
    protected $status;
    /**
     * @ORM\Column(type="date", nullable=true)
     * @Serializer\Groups({"availability"})
     */
    protected $firstNight;
    /**
     * @ORM\Column(type="date", nullable=true)
     * @Serializer\Groups({"availability"})
     */
    protected $lastNight;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $numAdult;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $numChild;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestTitle;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestFirstName;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestName;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestEmail;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestPhone;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestMobile;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestFax;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestAddress;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestCity;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestPostcode;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestCountry;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestArrivalTime;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestVoucher;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $guestComments;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $notes;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $message;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom1;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom2;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom3;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom4;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom5;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom6;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom7;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom8;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom9;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $custom10;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $flagColor;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $flagText;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $price;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $deposit;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $tax;
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $commission;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $referer;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $refererEditable;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $apiSource;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $apiReference;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $bookingTime;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $modified;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Channel")
     * @ORM\JoinColumn(name="channel_id", referencedColumnName="id")
     */
    protected $channel;
    protected $apiResponce;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Room
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * @return mixed
     */
    public function getBookId()
    {
        return $this->bookId;
    }

    /**
     * @param mixed $bookId
     */
    public function setBookId($bookId)
    {
        $this->bookId = $bookId;
    }

    /**
     * @return mixed
     */
    public function getUnitId()
    {
        return $this->unitId;
    }

    /**
     * @param mixed $unitId
     */
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    /**
     * @return mixed
     */
    public function getRoomQty()
    {
        return $this->roomQty;
    }

    /**
     * @param mixed $roomQty
     */
    public function setRoomQty($roomQty)
    {
        $this->roomQty = $roomQty;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getFirstNight()
    {
        return $this->firstNight;
    }

    /**
     * @param mixed $firstNight
     */
    public function setFirstNight($firstNight)
    {
        $this->firstNight = $firstNight;
    }

    /**
     * @return mixed
     */
    public function getLastNight()
    {
        return $this->lastNight;
    }

    /**
     * @param mixed $lastNight
     */
    public function setLastNight($lastNight)
    {
        $this->lastNight = $lastNight;
    }

    /**
     * @return mixed
     */
    public function getNumAdult()
    {
        return $this->numAdult;
    }

    /**
     * @param mixed $numAdult
     */
    public function setNumAdult($numAdult)
    {
        $this->numAdult = $numAdult;
    }

    /**
     * @return mixed
     */
    public function getNumChild()
    {
        return $this->numChild;
    }

    /**
     * @param mixed $numChild
     */
    public function setNumChild($numChild)
    {
        $this->numChild = $numChild;
    }

    /**
     * @return mixed
     */
    public function getGuestTitle()
    {
        return $this->guestTitle;
    }

    /**
     * @param mixed $guestTitle
     */
    public function setGuestTitle($guestTitle)
    {
        $this->guestTitle = $guestTitle;
    }

    /**
     * @return mixed
     */
    public function getGuestFirstName()
    {
        return $this->guestFirstName;
    }

    /**
     * @param mixed $guestFirstName
     */
    public function setGuestFirstName($guestFirstName)
    {
        $this->guestFirstName = $guestFirstName;
    }

    /**
     * @return mixed
     */
    public function getGuestName()
    {
        return $this->guestName;
    }

    /**
     * @param mixed $guestName
     */
    public function setGuestName($guestName)
    {
        $this->guestName = $guestName;
    }

    /**
     * @return mixed
     */
    public function getGuestEmail()
    {
        return $this->guestEmail;
    }

    /**
     * @param mixed $guestEmail
     */
    public function setGuestEmail($guestEmail)
    {
        $this->guestEmail = $guestEmail;
    }

    /**
     * @return mixed
     */
    public function getGuestPhone()
    {
        return $this->guestPhone;
    }

    /**
     * @param mixed $guestPhone
     */
    public function setGuestPhone($guestPhone)
    {
        $this->guestPhone = $guestPhone;
    }

    /**
     * @return mixed
     */
    public function getGuestMobile()
    {
        return $this->guestMobile;
    }

    /**
     * @param mixed $guestMobile
     */
    public function setGuestMobile($guestMobile)
    {
        $this->guestMobile = $guestMobile;
    }

    /**
     * @return mixed
     */
    public function getGuestFax()
    {
        return $this->guestFax;
    }

    /**
     * @param mixed $guestFax
     */
    public function setGuestFax($guestFax)
    {
        $this->guestFax = $guestFax;
    }

    /**
     * @return mixed
     */
    public function getGuestAddress()
    {
        return $this->guestAddress;
    }

    /**
     * @param mixed $guestAddress
     */
    public function setGuestAddress($guestAddress)
    {
        $this->guestAddress = $guestAddress;
    }

    /**
     * @return mixed
     */
    public function getGuestCity()
    {
        return $this->guestCity;
    }

    /**
     * @param mixed $guestCity
     */
    public function setGuestCity($guestCity)
    {
        $this->guestCity = $guestCity;
    }

    /**
     * @return mixed
     */
    public function getGuestPostcode()
    {
        return $this->guestPostcode;
    }

    /**
     * @param mixed $guestPostcode
     */
    public function setGuestPostcode($guestPostcode)
    {
        $this->guestPostcode = $guestPostcode;
    }

    /**
     * @return mixed
     */
    public function getGuestCountry()
    {
        return $this->guestCountry;
    }

    /**
     * @param mixed $guestCountry
     */
    public function setGuestCountry($guestCountry)
    {
        $this->guestCountry = $guestCountry;
    }

    /**
     * @return mixed
     */
    public function getGuestArrivalTime()
    {
        return $this->guestArrivalTime;
    }

    /**
     * @param mixed $guestArrivalTime
     */
    public function setGuestArrivalTime($guestArrivalTime)
    {
        $this->guestArrivalTime = $guestArrivalTime;
    }

    /**
     * @return mixed
     */
    public function getGuestVoucher()
    {
        return $this->guestVoucher;
    }

    /**
     * @param mixed $guestVoucher
     */
    public function setGuestVoucher($guestVoucher)
    {
        $this->guestVoucher = $guestVoucher;
    }

    /**
     * @return mixed
     */
    public function getGuestComments()
    {
        return $this->guestComments;
    }

    /**
     * @param mixed $guestComments
     */
    public function setGuestComments($guestComments)
    {
        $this->guestComments = $guestComments;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCustom1()
    {
        return $this->custom1;
    }

    /**
     * @param mixed $custom1
     */
    public function setCustom1($custom1)
    {
        $this->custom1 = $custom1;
    }

    /**
     * @return mixed
     */
    public function getCustom2()
    {
        return $this->custom2;
    }

    /**
     * @param mixed $custom2
     */
    public function setCustom2($custom2)
    {
        $this->custom2 = $custom2;
    }

    /**
     * @return mixed
     */
    public function getCustom3()
    {
        return $this->custom3;
    }

    /**
     * @param mixed $custom3
     */
    public function setCustom3($custom3)
    {
        $this->custom3 = $custom3;
    }

    /**
     * @return mixed
     */
    public function getCustom4()
    {
        return $this->custom4;
    }

    /**
     * @param mixed $custom4
     */
    public function setCustom4($custom4)
    {
        $this->custom4 = $custom4;
    }

    /**
     * @return mixed
     */
    public function getCustom5()
    {
        return $this->custom5;
    }

    /**
     * @param mixed $custom5
     */
    public function setCustom5($custom5)
    {
        $this->custom5 = $custom5;
    }

    /**
     * @return mixed
     */
    public function getCustom6()
    {
        return $this->custom6;
    }

    /**
     * @param mixed $custom6
     */
    public function setCustom6($custom6)
    {
        $this->custom6 = $custom6;
    }

    /**
     * @return mixed
     */
    public function getCustom7()
    {
        return $this->custom7;
    }

    /**
     * @param mixed $custom7
     */
    public function setCustom7($custom7)
    {
        $this->custom7 = $custom7;
    }

    /**
     * @return mixed
     */
    public function getCustom8()
    {
        return $this->custom8;
    }

    /**
     * @param mixed $custom8
     */
    public function setCustom8($custom8)
    {
        $this->custom8 = $custom8;
    }

    /**
     * @return mixed
     */
    public function getCustom9()
    {
        return $this->custom9;
    }

    /**
     * @param mixed $custom9
     */
    public function setCustom9($custom9)
    {
        $this->custom9 = $custom9;
    }

    /**
     * @return mixed
     */
    public function getCustom10()
    {
        return $this->custom10;
    }

    /**
     * @param mixed $custom10
     */
    public function setCustom10($custom10)
    {
        $this->custom10 = $custom10;
    }

    /**
     * @return mixed
     */
    public function getFlagColor()
    {
        return $this->flagColor;
    }

    /**
     * @param mixed $flagColor
     */
    public function setFlagColor($flagColor)
    {
        $this->flagColor = $flagColor;
    }

    /**
     * @return mixed
     */
    public function getFlagText()
    {
        return $this->flagText;
    }

    /**
     * @param mixed $flagText
     */
    public function setFlagText($flagText)
    {
        $this->flagText = $flagText;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * @param mixed $deposit
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;
    }

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param mixed $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
    }

    /**
     * @return mixed
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * @param mixed $commission
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;
    }

    /**
     * @return mixed
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param mixed $referer
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
    }

    /**
     * @return mixed
     */
    public function getRefererEditable()
    {
        return $this->refererEditable;
    }

    /**
     * @param mixed $refererEditable
     */
    public function setRefererEditable($refererEditable)
    {
        $this->refererEditable = $refererEditable;
    }

    /**
     * @return mixed
     */
    public function getApiSource()
    {
        return $this->apiSource;
    }

    /**
     * @param mixed $apiSource
     */
    public function setApiSource($apiSource)
    {
        $this->apiSource = $apiSource;
    }

    /**
     * @return mixed
     */
    public function getApiReference()
    {
        return $this->apiReference;
    }

    /**
     * @param mixed $apiReference
     */
    public function setApiReference($apiReference)
    {
        $this->apiReference = $apiReference;
    }

    /**
     * @return mixed
     */
    public function getBookingTime()
    {
        return $this->bookingTime;
    }

    /**
     * @param mixed $bookingTime
     */
    public function setBookingTime($bookingTime)
    {
        $this->bookingTime = $bookingTime;
    }

    /**
     * @return mixed
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param mixed $modified
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    }


    public function setFromBeds24($beds24_booking, Room $room = null)
    {
        if (isset($room)) {
            $this->room = $room;
        }

        $this->apiResponce = $beds24_booking;

        foreach ($beds24_booking as $k => $v) {
            if($k === "firstNight" || $k === "lastNight") {
                $this->$k = new \DateTime($v);
            } else if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    public function getApiResponce()
    {
        return $this->apiResponce;
    }


    /**
     * Set channel
     *
     * @param \AppBundle\Entity\Channel $channel
     * @return Booking
     */
    public function setChannel(\AppBundle\Entity\Channel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \AppBundle\Entity\Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    public function toBeds24Booking()
    {
        $res = array(
            "authentication" => array(
                "apiKey" => $this->getRoom()->getProperty()->getOwner()->getApiKey(),
                "propKey" => $this->getRoom()->getProperty()->getPropKey()
            )
        );

        foreach (get_class_methods($this) as $class_method) {
            if($this->startsWith($class_method, "get")) {
                try {
                    $val = call_user_func(array($this, $class_method));
                    if (empty($val) || is_object($val))
                        continue;
                    $res[$this->getFieldByMethodName($class_method)] = $val;
                } catch (\Exception $ex) {
                    
                }
            }
        }

        $res["roomId"] = $this->getRoom()->getRoomid();

        return $res;
    }

    private function startsWith($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    private function getFieldByMethodName($class_method)
    {
        $name = substr($class_method, 3);
        return lcfirst($name);
    }
}
