<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 09/03/16
 * Time: 17:02
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="agency")
 */
class Agency
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $dateEstablished;

    /**
     * @ORM\Column(type="date")
     */
    protected $dateJoined;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManagedBy", mappedBy="agency", orphanRemoval=true)
     */
    protected $managedProperties;
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
     * @return Agency
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
     * Set dateEstablished
     *
     * @param \DateTime $dateEstablished
     * @return Agency
     */
    public function setDateEstablished($dateEstablished)
    {
        $this->dateEstablished = $dateEstablished;

        return $this;
    }

    /**
     * Get dateEstablished
     *
     * @return \DateTime 
     */
    public function getDateEstablished()
    {
        return $this->dateEstablished;
    }

    /**
     * Set dateJoined
     *
     * @param \DateTime $dateJoined
     * @return Agency
     */
    public function setDateJoined($dateJoined)
    {
        $this->dateJoined = $dateJoined;

        return $this;
    }

    /**
     * Get dateJoined
     *
     * @return \DateTime 
     */
    public function getDateJoined()
    {
        return $this->dateJoined;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->managedProperties = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add managedProperties
     *
     * @param \AppBundle\Entity\ManagedBy $managedProperties
     * @return Agency
     */
    public function addManagedProperty(\AppBundle\Entity\ManagedBy $managedProperties)
    {
        $this->managedProperties[] = $managedProperties;

        return $this;
    }

    /**
     * Remove managedProperties
     *
     * @param \AppBundle\Entity\ManagedBy $managedProperties
     */
    public function removeManagedProperty(\AppBundle\Entity\ManagedBy $managedProperties)
    {
        $this->managedProperties->removeElement($managedProperties);
    }

    /**
     * Get managedProperties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getManagedProperties()
    {
        return $this->managedProperties;
    }
}
