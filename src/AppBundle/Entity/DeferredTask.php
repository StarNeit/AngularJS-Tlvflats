<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * DeferredTask
 * @ORM\Entity()
 * @ORM\Table(name="deferred_task")
 */
class DeferredTask
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $executeAt;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $executeCommand;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $params;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finishedAt;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $result;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $log;


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
     * Set executeAt
     *
     * @param \DateTime $executeAt
     * @return DeferredTask
     */
    public function setExecuteAt($executeAt)
    {
        $this->executeAt = $executeAt;

        return $this;
    }

    /**
     * Get executeAt
     *
     * @return \DateTime 
     */
    public function getExecuteAt()
    {
        return $this->executeAt;
    }

    /**
     * Set executeCommand
     *
     * @param string $executeCommand
     * @return DeferredTask
     */
    public function setExecuteCommand($executeCommand)
    {
        $this->executeCommand = $executeCommand;

        return $this;
    }

    /**
     * Get executeCommand
     *
     * @return string 
     */
    public function getExecuteCommand()
    {
        return $this->executeCommand;
    }

    /**
     * Set params
     *
     * @param string $params
     * @return DeferredTask
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return string 
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set finishedAt
     *
     * @param \DateTime $finishedAt
     * @return DeferredTask
     */
    public function setFinishedAt($finishedAt)
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * Get finishedAt
     *
     * @return \DateTime 
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * Set result
     *
     * @param string $result
     * @return DeferredTask
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set log
     *
     * @param string $log
     * @return DeferredTask
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return string 
     */
    public function getLog()
    {
        return $this->log;
    }
}
