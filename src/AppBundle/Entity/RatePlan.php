<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 13/03/16
 * Time: 16:08
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class RatePlan
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="rate_plan")
 */
class RatePlan
{
    const PERCENT=0;
    const PRICE=1;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $planType;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Owner")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $owner;
    /**
     * @ORM\Column(type="text")
     */
    protected $name;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RateScopeSet")
     */
    protected $rateScopeSet;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RateSteppingSet")
     */
    protected $rateSteppingSet;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RateSteppingDefinition", mappedBy="ratePlan", cascade={"all"}, orphanRemoval=true)
     */
    protected $rateSteppingDefinitions;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Rate", mappedBy="ratePlan", cascade={"all"}, orphanRemoval=true)
     */
    protected $rates;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RateStepping")
     */
    protected $defaultRateStepping;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set planType
     *
     * @param integer $planType
     * @return RatePlan
     */
    public function setPlanType($planType)
    {
        $this->planType = $planType;

        return $this;
    }

    /**
     * Get planType
     *
     * @return integer 
     */
    public function getPlanType()
    {
        return $this->planType;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return RatePlan
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
     * Set rateScopeSet
     *
     * @param \AppBundle\Entity\RateScopeSet $rateScopeSet
     * @return RatePlan
     */
    public function setRateScopeSet(\AppBundle\Entity\RateScopeSet $rateScopeSet = null)
    {
        $this->rateScopeSet = $rateScopeSet;

        return $this;
    }

    /**
     * Get rateScopeSet
     *
     * @return \AppBundle\Entity\RateScopeSet 
     */
    public function getRateScopeSet()
    {
        return $this->rateScopeSet;
    }

    /**
     * Set rateSteppingSet
     *
     * @param \AppBundle\Entity\RateSteppingSet $rateSteppingSet
     * @return RatePlan
     */
    public function setRateSteppingSet(\AppBundle\Entity\RateSteppingSet $rateSteppingSet = null)
    {
        $this->rateSteppingSet = $rateSteppingSet;

        return $this;
    }

    /**
     * Get rateSteppingSet
     *
     * @return \AppBundle\Entity\RateSteppingSet 
     */
    public function getRateSteppingSet()
    {
        return $this->rateSteppingSet;
    }
    
    /**
     * Add rates
     *
     * @param \AppBundle\Entity\Rate $rates
     * @return RatePlan
     */
    public function addRate(\AppBundle\Entity\Rate $rates)
    {
        $this->rates[] = $rates;

        return $this;
    }

    /**
     * Remove rates
     *
     * @param \AppBundle\Entity\Rate $rates
     */
    public function removeRate(\AppBundle\Entity\Rate $rates)
    {
        $this->rates->removeElement($rates);
    }

    /**
     * Get rates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Set defaultRateStepping
     *
     * @param \AppBundle\Entity\RateStepping $defaultRateStepping
     * @return RatePlan
     */
    public function setDefaultRateStepping(\AppBundle\Entity\RateStepping $defaultRateStepping = null)
    {
        $this->defaultRateStepping = $defaultRateStepping;

        return $this;
    }

    /**
     * Get defaultRateStepping
     *
     * @return \AppBundle\Entity\RateStepping 
     */
    public function getDefaultRateStepping()
    {
        return $this->defaultRateStepping;
    }

    /**
     * Add rateSteppingDefinitions
     *
     * @param \AppBundle\Entity\RateSteppingDefinition $rateSteppingDefinitions
     * @return RatePlan
     */
    public function addRateSteppingDefinition(\AppBundle\Entity\RateSteppingDefinition $rateSteppingDefinitions)
    {
        $this->rateSteppingDefinitions[] = $rateSteppingDefinitions;

        return $this;
    }

    /**
     * Remove rateSteppingDefinitions
     *
     * @param \AppBundle\Entity\RateSteppingDefinition $rateSteppingDefinitions
     */
    public function removeRateSteppingDefinition(\AppBundle\Entity\RateSteppingDefinition $rateSteppingDefinitions)
    {
        $this->rateSteppingDefinitions->removeElement($rateSteppingDefinitions);
    }

    /**
     * Get rateSteppingDefinitions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRateSteppingDefinitions()
    {
        return $this->rateSteppingDefinitions;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\Owner $owner
     * @return RatePlan
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
}
