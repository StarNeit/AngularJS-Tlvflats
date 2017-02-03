<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 18.07.2015
 * Time: 20:24
 */

namespace AppBundle\Entity;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="room")
 *
 * @ExclusionPolicy("all")
 */
class Room
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     * @Expose
     */
    protected $title;

    /**
     * @ORM\Column(type="float")
     * @Expose
     */
    protected $displayPrice;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     */
    protected $accomodate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose
     */
    protected $bedrooms;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose
     */
    protected $bathrooms;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose
     */
    protected $size;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose
     */
    protected $floor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose
     */
    protected $roomid;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose
     */
    protected $balkony;

    /**
     * @ORM\Column(type="decimal", nullable=true, precision=10, scale=2)
     */
    protected $basePrice;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" = 1})
     */
    protected $roomsAvailable = 1;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="rooms")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    protected $property;

    /**
     * @ORM\OneToMany(targetEntity="Booking", mappedBy="room", orphanRemoval=true)
     *
     */
    protected $bookings;

    /**
     * @ORM\OneToMany(targetEntity="RoomComponent", mappedBy="room", orphanRemoval=true, cascade={"all"})
     *
     * @Expose
     */
    protected $components;


    /**
     * @ORM\Column(type="float", nullable=true, precision=10, scale=2)
     * @Expose()
     */
    protected $cleaningFee;

    /**
     * @Expose()
     * @ORM\Column(type="float", nullable=true, precision=10, scale=2)
     */
    protected $deposit;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Room
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set displayPrice
     *
     * @param float $displayPrice
     * @return Room
     */
    public function setDisplayPrice($displayPrice)
    {
        $this->displayPrice = $displayPrice;

        return $this;
    }

    /**
     * Get displayPrice
     *
     * @return float 
     */
    public function getDisplayPrice()
    {
        return $this->displayPrice;
    }

    /**
     * Set accomodate
     *
     * @param integer $accomodate
     * @return Room
     */
    public function setAccomodate($accomodate)
    {
        $this->accomodate = $accomodate;

        return $this;
    }

    /**
     * Get accomodate
     *
     * @return integer 
     */
    public function getAccomodate()
    {
        return $this->accomodate;
    }

    /**
     * Set bedrooms
     *
     * @param integer $bedrooms
     * @return Room
     */
    public function setBedrooms($bedrooms)
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    /**
     * Get bedrooms
     *
     * @return integer 
     */
    public function getBedrooms()
    {
        return $this->bedrooms;
    }

    /**
     * Set bathrooms
     *
     * @param integer $bathrooms
     * @return Room
     */
    public function setBathrooms($bathrooms)
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    /**
     * Get bathrooms
     *
     * @return integer 
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return Room
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set floor
     *
     * @param integer $floor
     * @return Room
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;

        return $this;
    }

    /**
     * Get floor
     *
     * @return integer 
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set roomid
     *
     * @param integer $roomid
     * @return Room
     */
    public function setRoomid($roomid)
    {
        $this->roomid = $roomid;

        return $this;
    }

    /**
     * Get roomid
     *
     * @return integer 
     */
    public function getRoomid()
    {
        return $this->roomid;
    }

    /**
     * Set property
     *
     * @param \AppBundle\Entity\Property $property
     * @return Room
     */
    public function setProperty(\AppBundle\Entity\Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \AppBundle\Entity\Property 
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set balkony
     *
     * @param integer $balkony
     * @return Room
     */
    public function setBalkony($balkony)
    {
        $this->balkony = $balkony;

        return $this;
    }

    /**
     * Get balkony
     *
     * @return integer
     */
    public function getBalkony()
    {
        return $this->balkony;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bookings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add bookings
     *
     * @param \AppBundle\Entity\Booking $bookings
     * @return Room
     */
    public function addBooking(\AppBundle\Entity\Booking $bookings)
    {
        $this->bookings[] = $bookings;

        return $this;
    }

    /**
     * Remove bookings
     *
     * @param \AppBundle\Entity\Booking $bookings
     */
    public function removeBooking(\AppBundle\Entity\Booking $bookings)
    {
        $this->bookings->removeElement($bookings);
    }

    /**
     * Get bookings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBookings()
    {
        return $this->bookings;
    }

    /**
     * Set basePrice
     *
     * @param float $basePrice
     * @return Room
     */
    public function setBasePrice($basePrice)
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    /**
     * Get basePrice
     *
     * @return float
     */
    public function getBasePrice()
    {
        return $this->basePrice;
    }

    /**
     * Set roomsAvailable
     *
     * @param integer $roomsAvailable
     * @return Room
     */
    public function setRoomsAvailable($roomsAvailable)
    {
        $this->roomsAvailable = $roomsAvailable;

        return $this;
    }

    /**
     * Get roomsAvailable
     *
     * @return integer 
     */
    public function getRoomsAvailable()
    {
        return $this->roomsAvailable;
    }

    /**
     * Set cleaningFee
     *
     * @param float $cleaningFee
     * @return Room
     */
    public function setCleaningFee($cleaningFee)
    {
        $this->cleaningFee = $cleaningFee;

        return $this;
    }

    /**
     * Get cleaningFee
     *
     * @return float
     */
    public function getCleaningFee()
    {
        return $this->cleaningFee;
    }

    /**
     * Set deposit
     *
     * @param float $deposit
     * @return Room
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;

        return $this;
    }

    /**
     * Get deposit
     *
     * @return float
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * Add components
     *
     * @param \AppBundle\Entity\RoomComponent $components
     * @return Room
     */
    public function addComponent(\AppBundle\Entity\RoomComponent $components)
    {
        $this->components[] = $components;

        return $this;
    }

    /**
     * Remove components
     *
     * @param \AppBundle\Entity\RoomComponent $components
     */
    public function removeComponent(\AppBundle\Entity\RoomComponent $components)
    {
        $this->components->removeElement($components);
    }

    /**
     * Get components
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComponents()
    {
        return $this->components;
    }
}
