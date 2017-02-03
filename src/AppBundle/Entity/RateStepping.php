<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 27/02/16
 * Time: 17:59
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class RateStepping
 * @package AppBundle\Entity\Prices
 * @ORM\Entity()
 * @ORM\Table(name="rate_stepping")
 */
class RateStepping
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RateSteppingSet", inversedBy="rateSteppings")
     * @ORM\JoinColumn(name="rate_stepping_set_id")
     */
    protected $rateSteppingSet;

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
     * @return RateStepping
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
     * Set rateSteppingSet
     *
     * @param \AppBundle\Entity\RateSteppingSet $rateSteppingSet
     * @return RateStepping
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
}
