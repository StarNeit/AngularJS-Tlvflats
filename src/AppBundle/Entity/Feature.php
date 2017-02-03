<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 12.07.2015
 * Time: 17:39
 */

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="feature")
 *
 * @ExclusionPolicy("all")
 */
class Feature
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @Expose
     */
    protected $fa_name;

    /**
     * @ORM\OneToMany(targetEntity="PropertyFeature", mappedBy="feature", orphanRemoval=true)
     */
    protected $propertyFeatures;


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
     * @return Feature
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
     * Set fa_name
     *
     * @param string $faName
     * @return Feature
     */
    public function setFaName($faName)
    {
        $this->fa_name = $faName;

        return $this;
    }

    /**
     * Get fa_name
     *
     * @return string 
     */
    public function getFaName()
    {
        return $this->fa_name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->propertyFeatures = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add propertyFeatures
     *
     * @param \AppBundle\Entity\PropertyFeature $propertyFeatures
     * @return Feature
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
}
