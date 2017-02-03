<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 30/05/16
 * Time: 12:08
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cron_log")
 */
class CronLog
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
    protected $commandName;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $lastRun;

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
     * Set commandName
     *
     * @param string $commandName
     * @return CronLog
     */
    public function setCommandName($commandName)
    {
        $this->commandName = $commandName;

        return $this;
    }

    /**
     * Get commandName
     *
     * @return string 
     */
    public function getCommandName()
    {
        return $this->commandName;
    }

    /**
     * Set lastRun
     *
     * @param \DateTime $lastRun
     * @return CronLog
     */
    public function setLastRun($lastRun)
    {
        $this->lastRun = $lastRun;

        return $this;
    }

    /**
     * Get lastRun
     *
     * @return \DateTime 
     */
    public function getLastRun()
    {
        return $this->lastRun;
    }
}
