<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 27/02/16
 * Time: 17:30
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RateScopeSet
 * @package AppBundle\Entity\Prices
 * @ORM\Entity()
 * @ORM\Table(name="rate_scope_set")
 */
class RateScopeSet
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
     * @ORM\OneToMany(targetEntity="RateScope", mappedBy="rateScopeSet", cascade={"all"})
     */
    protected $rateScopes;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rateScopes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return RateScopeSet
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
     * @return RateScopeSet
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
     * Add rateScopes
     *
     * @param RateScope $rateScopes
     * @return RateScopeSet
     */
    public function addRateScope(RateScope $rateScopes)
    {
        $this->rateScopes[] = $rateScopes;

        return $this;
    }

    /**
     * Remove rateScopes
     *
     * @param RateScope $rateScopes
     */
    public function removeRateScope(RateScope $rateScopes)
    {
        $this->rateScopes->removeElement($rateScopes);
    }

    /**
     * Get rateScopes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRateScopes()
    {
        return $this->rateScopes;
    }
}
