<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/05/16
 * Time: 15:11
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="channel")
 */
class Channel
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
    protected $serviceName;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ChannelOption", mappedBy="channel")
     */
    protected $options;

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
     * @return Channel
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
     * Set serviceName
     *
     * @param string $serviceName
     * @return Channel
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * Get serviceName
     *
     * @return string 
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add options
     *
     * @param \AppBundle\Entity\ChannelOption $options
     * @return Channel
     */
    public function addOption(\AppBundle\Entity\ChannelOption $options)
    {
        $this->options[] = $options;

        return $this;
    }

    /**
     * Remove options
     *
     * @param \AppBundle\Entity\ChannelOption $options
     */
    public function removeOption(\AppBundle\Entity\ChannelOption $options)
    {
        $this->options->removeElement($options);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $name string
     * @return ChannelOption
     */
    public function getOption($name) {
        /** @var ChannelOption $option */
        foreach ($this->options as $option) {
            if($option->getName() === $name) {
                return $option;
            }
        }
        return null;
    }
}
