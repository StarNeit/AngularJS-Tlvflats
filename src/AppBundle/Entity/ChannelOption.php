<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 22/05/16
 * Time: 11:10
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="channel_options")
 */
class ChannelOption
{
    
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $val;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Channel", inversedBy="options")
     * @ORM\JoinColumn(name="channel_id", referencedColumnName="id")
     */
    protected $channel;
    
    protected $description;

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
     * @return ChannelOption
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
     * Set val
     *
     * @param string $val
     * @return ChannelOption
     */
    public function setVal($val)
    {
        $this->val = $val;

        return $this;
    }

    /**
     * Get val
     *
     * @return string 
     */
    public function getVal()
    {
        return $this->val;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ChannelOption
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set channel
     *
     * @param \AppBundle\Entity\Channel $channel
     * @return ChannelOption
     */
    public function setChannel(\AppBundle\Entity\Channel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \AppBundle\Entity\Channel 
     */
    public function getChannel()
    {
        return $this->channel;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
