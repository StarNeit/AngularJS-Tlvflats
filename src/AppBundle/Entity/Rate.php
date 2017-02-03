<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 13/03/16
 * Time: 16:17
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rate
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="rate")
 */
class Rate
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RatePlan", inversedBy="rates")
     */
    protected $ratePlan;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RateStepping")
     */
    protected $rateStepping;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RateScope")
     */
    protected $rateScope;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $rate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Currency")
     */
    protected $currency;

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
     * Set rate
     *
     * @param integer $rate
     * @return Rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        return $this->rate;
    }
    
    /**
     * Set ratePlan
     *
     * @param \AppBundle\Entity\RatePlan $ratePlan
     * @return Rate
     */
    public function setRatePlan(\AppBundle\Entity\RatePlan $ratePlan = null)
    {
        $this->ratePlan = $ratePlan;

        return $this;
    }

    /**
     * Get ratePlan
     *
     * @return \AppBundle\Entity\RatePlan 
     */
    public function getRatePlan()
    {
        return $this->ratePlan;
    }

    /**
     * Set rateStepping
     *
     * @param \AppBundle\Entity\RateStepping $rateStepping
     * @return Rate
     */
    public function setRateStepping(\AppBundle\Entity\RateStepping $rateStepping = null)
    {
        $this->rateStepping = $rateStepping;

        return $this;
    }

    /**
     * Get rateStepping
     *
     * @return \AppBundle\Entity\RateStepping 
     */
    public function getRateStepping()
    {
        return $this->rateStepping;
    }

    /**
     * Set rateScope
     *
     * @param \AppBundle\Entity\RateScope $rateScope
     * @return Rate
     */
    public function setRateScope(\AppBundle\Entity\RateScope $rateScope = null)
    {
        $this->rateScope = $rateScope;

        return $this;
    }

    /**
     * Get rateScope
     *
     * @return \AppBundle\Entity\RateScope 
     */
    public function getRateScope()
    {
        return $this->rateScope;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Rate
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }


    /**
     * Set currency
     *
     * @param \AppBundle\Entity\Currency $currency
     * @return Rate
     */
    public function setCurrency(\AppBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \AppBundle\Entity\Currency 
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
