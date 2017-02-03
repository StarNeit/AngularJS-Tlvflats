<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 12.07.2015
 * Time: 17:32
 */

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PropertyRepository")
 * @ORM\Table(name="property")
 *
 * @ExclusionPolicy("all")
 */
class Property
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
     * @ORM\Column(type="string", length=100)
     */
    protected $propKey;

    /**
     * @ORM\Column(type="string", length=255)
     * @Expose
     */
    protected $address;

    /**
     * @ORM\Column(type="text")
     * @Expose
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Expose
     */
    protected $shortDescription;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Expose
     */
    protected $longDescription;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Expose
     */
    protected $propid;

    /**
     * @ORM\Column(type="boolean")
     * @Expose
     */
    protected $singleRoom;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Expose
     */
    protected $longitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Expose
     */
    protected $latitude;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Expose
     */
    protected $instantBook;

    /**
     * @ORM\ManyToOne(targetEntity="Owner", inversedBy="properties")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * @ORM\OneToMany(targetEntity="PropertyFeature", mappedBy="property", orphanRemoval=true)
     *
     * @Expose
     */
    protected $propertyFeatures;

    /**
     * @ORM\OneToMany(targetEntity="PropertyPlace", mappedBy="property", orphanRemoval=true)
     *
     * @Expose
     */
    protected $propertyPlaces;

    /**
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     */
    protected $mainPlace;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="property", orphanRemoval=true)
     *
     * @Expose
     */
    protected $rooms;


    /**
     * @ORM\OneToMany(targetEntity="PropertyImage", mappedBy="property", orphanRemoval=true)
     *
     * @Expose
     */
    protected $images;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManagedBy", mappedBy="property", orphanRemoval=true)
     */
    protected $managedBy;

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
     * Set propKey
     *
     * @param string $propKey
     * @return Property
     */
    public function setPropKey($propKey)
    {
        $this->propKey = $propKey;

        return $this;
    }

    /**
     * Get propKey
     *
     * @return string 
     */
    public function getPropKey()
    {
        return $this->propKey;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Property
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
     * Set title
     *
     * @param string $title
     * @return Property
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
     * Set shortDescription
     *
     * @param string $shortDescription
     * @return Property
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get shortDescription
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set longDescription
     *
     * @param string $longDescription
     * @return Property
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    /**
     * Get longDescription
     *
     * @return string 
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set propid
     *
     * @param integer $propid
     * @return Property
     */
    public function setPropid($propid)
    {
        $this->propid = $propid;

        return $this;
    }

    /**
     * Get propid
     *
     * @return integer 
     */
    public function getPropid()
    {
        return $this->propid;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\Owner $owner
     * @return Property
     */
    public function setOwner(\AppBundle\Entity\Owner $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\Owner 
     */
    public function getOwner()
    {
        return $this->owner;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->propertyFeatures = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rooms =  new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add propertyFeatures
     *
     * @param \AppBundle\Entity\PropertyFeature $propertyFeatures
     * @return Property
     */
    public function addPropertyFeature(\AppBundle\Entity\PropertyFeature $propertyFeatures)
    {
        $this->propertyFeatures[] = $propertyFeatures;

        return $this;
    }

    /**
     * Remove propertyFeatures
     *
     * @param \AppBundle\Entity\PropertyFeature $propertyFeatures
     */
    public function removePropertyFeature(\AppBundle\Entity\PropertyFeature $propertyFeatures)
    {
        $this->propertyFeatures->removeElement($propertyFeatures);
    }

    /**
     * Get propertyFeatures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPropertyFeatures()
    {
        return $this->propertyFeatures;
    }

    /**
     * Add images
     *
     * @param \AppBundle\Entity\PropertyImage $images
     * @return Property
     */
    public function addImage(\AppBundle\Entity\PropertyImage $images)
    {
        $this->images[] = $images;

        return $this;
    }

    /**
     * Remove images
     *
     * @param \AppBundle\Entity\PropertyImage $images
     */
    public function removeImage(\AppBundle\Entity\PropertyImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set singleRoom
     *
     * @param boolean $singleRoom
     * @return Property
     */
    public function setSingleRoom($singleRoom)
    {
        $this->singleRoom = $singleRoom;

        return $this;
    }

    /**
     * Get singleRoom
     *
     * @return boolean 
     */
    public function getSingleRoom()
    {
        return $this->singleRoom;
    }

    /**
     * Add rooms
     *
     * @param \AppBundle\Entity\Room $rooms
     * @return Property
     */
    public function addRoom(\AppBundle\Entity\Room $rooms)
    {
        $this->rooms[] = $rooms;

        return $this;
    }

    /**
     * Remove rooms
     *
     * @param \AppBundle\Entity\Room $rooms
     */
    public function removeRoom(\AppBundle\Entity\Room $rooms)
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
     * Set longitude
     *
     * @param float $longitude
     * @return Property
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Property
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set instantBook
     *
     * @param boolean $instantBook
     * @return Property
     */
    public function setInstantBook($instantBook)
    {
        $this->instantBook = $instantBook;

        return $this;
    }

    /**
     * Get instantBook
     *
     * @return boolean 
     */
    public function getInstantBook()
    {
        return $this->instantBook;
    }

    /**
     * Add propertyPlaces
     *
     * @param \AppBundle\Entity\PropertyPlace $propertyPlaces
     * @return Property
     */
    public function addPropertyPlace(\AppBundle\Entity\PropertyPlace $propertyPlaces)
    {
        $this->propertyPlaces[] = $propertyPlaces;

        return $this;
    }

    /**
     * Remove propertyPlaces
     *
     * @param \AppBundle\Entity\PropertyPlace $propertyPlaces
     */
    public function removePropertyPlace(\AppBundle\Entity\PropertyPlace $propertyPlaces)
    {
        $this->propertyPlaces->removeElement($propertyPlaces);
    }

    /**
     * Get propertyPlaces
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPropertyPlaces()
    {
        return $this->propertyPlaces;
    }

    /**
     * Set mainPlace
     *
     * @param \AppBundle\Entity\Place $mainPlace
     * @return Property
     */
    public function setMainPlace(\AppBundle\Entity\Place $mainPlace = null)
    {
        $this->mainPlace = $mainPlace;

        return $this;
    }

    /**
     * Get mainPlace
     *
     * @return \AppBundle\Entity\Place 
     */
    public function getMainPlace()
    {
        return $this->mainPlace;
    }

    /**
     * Add managedBy
     *
     * @param \AppBundle\Entity\ManagedBy $managedBy
     * @return Property
     */
    public function addManagedBy(\AppBundle\Entity\ManagedBy $managedBy)
    {
        $this->managedBy[] = $managedBy;

        return $this;
    }

    /**
     * Remove managedBy
     *
     * @param \AppBundle\Entity\ManagedBy $managedBy
     */
    public function removeManagedBy(\AppBundle\Entity\ManagedBy $managedBy)
    {
        $this->managedBy->removeElement($managedBy);
    }

    /**
     * Get managedBy
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getManagedBy()
    {
        return $this->managedBy;
    }
}
