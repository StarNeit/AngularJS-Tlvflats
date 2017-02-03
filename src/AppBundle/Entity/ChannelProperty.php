<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 22/05/16
 * Time: 10:59
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="channel_property")
 */
class ChannelProperty implements JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Channel")
     * @ORM\JoinColumn(name="channel_ref_id", referencedColumnName="id")
     */
    protected $channel;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Property")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    protected $property;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ChannelRoom", mappedBy="property", orphanRemoval=true, cascade={"all"})
     */
    protected $rooms;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $channelId;

    /** @ORM\Column(type="string", length=255) */
    protected $title;

    /** @ORM\Column(type="string", length=255) */
    protected $address;

    /** @ORM\Column(type="text") */
    protected $apiResponse;

    /** @ORM\Column(type="text") */
    protected $description;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rooms = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return ChannelProperty
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
     * Set channel
     *
     * @param \AppBundle\Entity\Channel $channel
     * @return ChannelProperty
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

    /**
     * Set property
     *
     * @param \AppBundle\Entity\Property $property
     * @return ChannelProperty
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
     * Add rooms
     *
     * @param \AppBundle\Entity\ChannelRoom $rooms
     * @return ChannelProperty
     */
    public function addRoom(\AppBundle\Entity\ChannelRoom $rooms)
    {
        $this->rooms[] = $rooms;

        return $this;
    }

    /**
     * Remove rooms
     *
     * @param \AppBundle\Entity\ChannelRoom $rooms
     */
    public function removeRoom(\AppBundle\Entity\ChannelRoom $rooms)
    {
        $this->rooms->removeElement($rooms);
    }

    /**
     * Get rooms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ChannelProperty
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
     * Set address
     *
     * @param string $address
     * @return ChannelProperty
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set apiResponse
     *
     * @param string $apiResponse
     * @return ChannelProperty
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

    /**
     * Set description
     *
     * @param string $description
     * @return ChannelProperty
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
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            'title' => $this->title,
            'address' => $this->address,
            'channelId' => $this->channelId,
            'description' => $this->description,
            'value' => $this->channelId,
            'label' => $this->title." | ".$this->address
        ];
    }
}
