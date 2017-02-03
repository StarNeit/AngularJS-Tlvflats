<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 27/02/16
 * Time: 17:30
 */

namespace AppBundle\Entity;
use AppBundle\Entity\Owner;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PriceSteppingSet
 * @package AppBundle\Entity\Prices
 * @ORM\Entity
 * @ORM\Table(name="rate_stepping_set")
 */
class RateSteppingSet
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
     * @ORM\ManyToOne(targetEntity="Owner")
     * @ORM\JoinColumn(name="owner_id")
     */
    protected $owner;

    /**
     * @ORM\OneToMany(targetEntity="RateStepping", mappedBy="rateSteppingSet", cascade={"all"})
     */
    protected $rateSteppings;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rateSteppings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return RateSteppingSet
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
     * Set owner
     *
     * @param Owner $owner
     * @return RateSteppingSet
     */
    public function setOwner(Owner $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return Owner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add rateSteppings
     *
     * @param RateStepping $rateSteppings
     * @return RateSteppingSet
     */
    public function addRateStepping(RateStepping $rateSteppings)
    {
        $this->rateSteppings[] = $rateSteppings;

        return $this;
    }

    /**
     * Remove rateSteppings
     *
     * @param RateStepping $rateSteppings
     */
    public function removeRateStepping(RateStepping $rateSteppings)
    {
        $this->rateSteppings->removeElement($rateSteppings);
    }

    /**
     * Get rateSteppings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRateSteppings()
    {
        return $this->rateSteppings;
    }
}
