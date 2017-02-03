<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 09/03/16
 * Time: 17:33
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="email_account")
 */
class EmailAccount
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Owner", inversedBy="emails")
     */
    protected $owner;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     */
    protected $email;
    /**
     * @ORM\Column(type="text")
     */
    protected $serverLogin;
    /**
     * @ORM\Column(type="text")
     */
    protected $serverHost;
    /**
     * @ORM\Column(type="integer")
     */
    protected $serverPort;
    /**
     * @ORM\Column(type="text")
     */
    protected $serverPassword;

    protected $valid = "NA";
    protected $checkOnCreate;

    /**
     * @return mixed
     */
    public function getCheckOnCreate()
    {
        return $this->checkOnCreate;
    }

    /**
     * @param mixed $checkOnCreate
     * @return EmailAccount
     */
    public function setCheckOnCreate($checkOnCreate)
    {
        $this->checkOnCreate = $checkOnCreate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param mixed $valid
     * @return EmailAccount
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
        return $this;
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
     * Set email
     *
     * @param string $email
     * @return EmailAccount
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set serverLogin
     *
     * @param string $serverLogin
     * @return EmailAccount
     */
    public function setServerLogin($serverLogin)
    {
        $this->serverLogin = $serverLogin;

        return $this;
    }

    /**
     * Get serverLogin
     *
     * @return string
     */
    public function getServerLogin()
    {
        return $this->serverLogin;
    }

    /**
     * Set serverHost
     *
     * @param string $serverHost
     * @return EmailAccount
     */
    public function setServerHost($serverHost)
    {
        $this->serverHost = $serverHost;

        return $this;
    }

    /**
     * Get serverHost
     *
     * @return string
     */
    public function getServerHost()
    {
        return $this->serverHost;
    }

    /**
     * Set serverPort
     *
     * @param integer $serverPort
     * @return EmailAccount
     */
    public function setServerPort($serverPort)
    {
        $this->serverPort = $serverPort;

        return $this;
    }

    /**
     * Get serverPort
     *
     * @return integer
     */
    public function getServerPort()
    {
        return $this->serverPort;
    }

    /**
     * Set serverPassword
     *
     * @param string $serverPassword
     * @return EmailAccount
     */
    public function setServerPassword($serverPassword)
    {
        $this->serverPassword = $serverPassword;

        return $this;
    }

    /**
     * Get serverPassword
     *
     * @return string
     */
    public function getServerPassword()
    {
        return $this->serverPassword;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\Owner $owner
     * @return EmailAccount
     */
    public function setOwner(\AppBundle\Entity\Owner $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\Owner
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
