<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 12.07.2015
 * Time: 17:40
 */

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="property_feature")
 *
 * @ExclusionPolicy("all")
 */
class PropertyFeature
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $attribure;

    /**
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="propertyFeatures")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    protected $property;


    /**
     * @ORM\ManyToOne(targetEntity="Feature", inversedBy="propertyFeatures")
     * @ORM\JoinColumn(name="feature_id", referencedColumnName="id")
     *
     * @Expose
     */
    protected $feature;

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
     * Set attribure
     *
     * @param string $attribure
     * @return PropertyFeature
     */
    public function setAttribure($attribure)
    {
        $this->attribure = $attribure;

        return $this;
    }

    /**
     * Get attribure
     *
     * @return string 
     */
    public function getAttribure()
    {
        return $this->attribure;
    }

    /**
     * Set property
     *
     * @param \AppBundle\Entity\Property $property
     * @return PropertyFeature
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
     * Set feature
     *
     * @param \AppBundle\Entity\Feature $feature
     * @return PropertyFeature
     */
    public function setFeature(\AppBundle\Entity\Feature $feature = null)
    {
        $this->feature = $feature;

        return $this;
    }

    /**
     * Get feature
     *
     * @return \AppBundle\Entity\Feature 
     */
    public function getFeature()
    {
        return $this->feature;
    }
}
