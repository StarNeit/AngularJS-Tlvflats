<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 27/02/16
 * Time: 17:41
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class RateScope
 * @package AppBundle\Entity\Prices
 * @ORM\Entity()
 * @ORM\Table(name="rate_scope")
 */
class RateScope
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
    protected $displayName;

    /**
     * @ORM\Column(type="integer", options={"default"=1})
     */
    protected $minDays = 1;

    /**
     * @ORM\ManyToOne(targetEntity="RateScopeSet", inversedBy="rateScopes")
     * @ORM\JoinColumn(name="rate_scope_set_id")
     */
    protected $rateScopeSet;


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
     * Set displayName
     *
     * @param string $displayName
     * @return RateScope
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set minDays
     *
     * @param integer $minDays
     * @return RateScope
     */
    public function setMinDays($minDays)
    {
        $this->minDays = $minDays;

        return $this;
    }

    /**
     * Get minDays
     *
     * @return integer 
     */
    public function getMinDays()
    {
        return $this->minDays;
    }

    /**
     * Set rateScopeSet
     *
     * @param RateScopeSet $rateScopeSet
     * @return RateScope
     */
    public function setRateScopeSet(RateScopeSet $rateScopeSet = null)
    {
        $this->rateScopeSet = $rateScopeSet;

        return $this;
    }

    /**
     * Get rateScopeSet
     *
     * @return RateScopeSet
     */
    public function getRateScopeSet()
    {
        return $this->rateScopeSet;
    }
}
