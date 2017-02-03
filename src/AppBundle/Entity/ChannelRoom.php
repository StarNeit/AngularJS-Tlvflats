<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 22/05/16
 * Time: 10:59
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="channel_room")
 */
class ChannelRoom
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ChannelProperty", inversedBy="rooms")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    protected $property;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Room")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     */
    protected $room;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $channelId;

    /** @ORM\Column(type="string", length=255) */
    protected $title;

    /** @ORM\Column(type="text") */
    protected $description;

    /** @ORM\Column(type="text") */
    protected $apiResponse;

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
     * Set channelId
     *
     * @param string $channelId
     * @return ChannelRoom
     */
    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * Get channelId
     *
     * @return string 
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * Set property
     *
     * @param \AppBundle\Entity\ChannelProperty $property
     * @return ChannelRoom
     */
    public function setProperty(\AppBundle\Entity\ChannelProperty $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return \AppBundle\Entity\ChannelProperty 
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set room
     *
     * @param \AppBundle\Entity\Room $room
     * @return ChannelRoom
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

    /**
     * Set title
     *
     * @param string $title
     * @return ChannelRoom
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
     * Set description
     *
     * @param string $description
     * @return ChannelRoom
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set apiResponse
     *
     * @param string $apiResponse
     * @return ChannelRoom
     */
    public function setApiResponse($apiResponse)
    {
        $this->apiResponse = $apiResponse;

        return $this;
    }

    /**
     * Get apiResponse
     *
     * @return string 
     */
    public function getApiResponse()
    {
        return $this->apiResponse;
    }
}
