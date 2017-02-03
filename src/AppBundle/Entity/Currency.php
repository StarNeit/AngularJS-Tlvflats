<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 13/03/16
 * Time: 16:58
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Currency
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="currency")
 */
class Currency
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
     * @ORM\Column(type="string", length=5)
     */
    protected $symbol;
    /**
     * @ORM\Column(type="string", length=3)
     */
    protected $code;
    /**
     * @ORM\Column(type="boolean", name="put_before")
     */
    protected $before;

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
     * @return Currency
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
     * Set symbol
     *
     * @param string $symbol
     * @return Currency
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string 
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Currency
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set before
     *
     * @param boolean $before
     * @return Currency
     */
    public function setBefore($before)
    {
        $this->before = $before;

        return $this;
    }

    /**
     * Get before
     *
     * @return boolean 
     */
    public function getBefore()
    {
        return $this->before;
    }
}
