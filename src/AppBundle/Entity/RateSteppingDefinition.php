<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 13/03/16
 * Time: 16:13
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RateSteppingDefinition
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="rate_stepping_definition")
 */
class RateSteppingDefinition
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /** @ORM\ManyToOne(targetEntity="AppBundle\Entity\RatePlan", inversedBy="rateSteppingDefinition") */
    protected $ratePlan;
    /** @ORM\Column(type="date") */
    protected $dateEnd;
    /** @ORM\Column(type="date") */
    protected $dateStart;
    /** @ORM\ManyToOne(targetEntity="AppBundle\Entity\RateStepping") */
    protected $rateStepping;

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
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return RateSteppingDefinition
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return RateSteppingDefinition
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set ratePlan
     *
     * @param \AppBundle\Entity\RatePlan $ratePlan
     * @return RateSteppingDefinition
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
     * @return RateSteppingDefinition
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
}
