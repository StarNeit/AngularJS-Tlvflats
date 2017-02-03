<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 10/07/16
 * Time: 12:28
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
 * @ORM\Table(name="room_component")
 *
 * @ExclusionPolicy("all")
 */
class RoomComponent
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
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="components")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     */
    protected $room;

    /**
     * @ORM\Column(type="integer")
     *
     * @Expose()
     */
    protected $size;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Expose()
     */
    protected $amenities;


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
     * Set name
     *
     * @param string $name
     * @return RoomComponent
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return RoomComponent
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
     * Set amenities
     *
     * @param array $amenities
     * @return RoomComponent
     */
    public function setAmenities($amenities)
    {
        $this->amenities = $amenities;

        return $this;
    }

    /**
     * Get amenities
     *
     * @return array 
     */
    public function getAmenities()
    {
        return $this->amenities;
    }

    /**
     * Set room
     *
     * @param \AppBundle\Entity\Room $room
     * @return RoomComponent
     */
    public function setRoom(\AppBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \AppBundle\Entity\Room 
     */
    public function getRoom()
    {
        return $this->room;
    }
}
