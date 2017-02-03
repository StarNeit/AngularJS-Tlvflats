<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 17:30
 */

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="property_place")
 *
 * @ExclusionPolicy("all")
 */
class PropertyPlace
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="propertyPlaces")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    protected $property;


    /**
     * @ORM\ManyToOne(targetEntity="Place")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id")
     *
     * @Expose
     */
    protected $place;


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
     * Set property
     *
     * @param \AppBundle\Entity\Property $property
     * @return PropertyPlace
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
     * Set place
     *
     * @param \AppBundle\Entity\Place $place
     * @return PropertyPlace
     */
    public function setPlace(\AppBundle\Entity\Place $place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return \AppBundle\Entity\Place 
     */
    public function getPlace()
    {
        return $this->place;
    }
}
