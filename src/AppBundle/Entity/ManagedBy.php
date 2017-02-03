<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 09/03/16
 * Time: 19:19
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="managed_by")
 */
class ManagedBy
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Property", inversedBy="managedBy")
     */
    protected $property;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Owner", inversedBy="managedProperties")
     */
    protected $owner;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agency", inversedBy="managedProperties")
     */
    protected $agency;

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
     * @return ManagedBy
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
     * Set owner
     *
     * @param \AppBundle\Entity\Owner $owner
     * @return ManagedBy
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
     * Set agency
     *
     * @param \AppBundle\Entity\Agency $agency
     * @return ManagedBy
     */
    public function setAgency(\AppBundle\Entity\Agency $agency = null)
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * Get agency
     *
     * @return \AppBundle\Entity\Agency 
     */
    public function getAgency()
    {
        return $this->agency;
    }
}
