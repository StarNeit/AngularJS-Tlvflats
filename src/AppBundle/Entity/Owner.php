<?php
/**
 * Created by IntelliJ IDEA.
 * User: hp1
 * Date: 12.07.2015
 * Time: 17:34
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="owner")
 */
class Owner implements UserInterface, EquatableInterface, \Serializable
{

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $passHash;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $apiKey;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $admin = false;


    /**
     * @ORM\OneToMany(targetEntity="Property", mappedBy="owner", orphanRemoval=true)
     */
    protected $properties;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EmailAccount", mappedBy="owner", orphanRemoval=true)
     */
    protected $emails;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ManagedBy", mappedBy="owner", orphanRemoval=true)
     */
    protected $managedProperties;
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
     * Set login
     *
     * @param string $login
     * @return Owner
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set passHash
     *
     * @param string $passHash
     * @return Owner
     */
    public function setPassHash($passHash)
    {
        $this->passHash = $passHash;

        return $this;
    }

    /**
     * Get passHash
     *
     * @return string 
     */
    public function getPassHash()
    {
        return $this->passHash;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     * @return Owner
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
    
    /**
     * Add properties
     *
     * @param \AppBundle\Entity\Property $properties
     * @return Owner
     */
    public function addProperty(\AppBundle\Entity\Property $properties)
    {
        $this->properties[] = $properties;

        return $this;
    }

    /**
     * Remove properties
     *
     * @param \AppBundle\Entity\Property $properties
     */
    public function removeProperty(\AppBundle\Entity\Property $properties)
    {
        $this->properties->removeElement($properties);
    }

    /**
     * Get properties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * Also implementation should consider that $user instance may implement
     * the extended user interface `AdvancedUserInterface`.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof Owner) {
            return false;
        }

        if ($this->passHash !== $user->getPassword()) {
            return false;
        }

        if ($this->login !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return Role[] The user roles
     */
    public function getRoles()
    {
        $arr = array(new Role("ROLE_ADMIN"));
        if($this->getAdmin()) {
            array_push($arr, new Role("ROLE_SUPERADMIN"));
        }
        return $arr;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->getPassHash();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getLogin();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize(array(
            $this->id, $this->login, $this->passHash, $this->apiKey
        ));
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        list(
            $this->id, $this->login, $this->passHash, $this->apiKey
        ) = unserialize($serialized);
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return Owner
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Add emails
     *
     * @param \AppBundle\Entity\EmailAccount $emails
     * @return Owner
     */
    public function addEmail(\AppBundle\Entity\EmailAccount $emails)
    {
        $this->emails[] = $emails;

        return $this;
    }

    /**
     * Remove emails
     *
     * @param \AppBundle\Entity\EmailAccount $emails
     */
    public function removeEmail(\AppBundle\Entity\EmailAccount $emails)
    {
        $this->emails->removeElement($emails);
    }

    /**
     * Get emails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Add managedProperties
     *
     * @param \AppBundle\Entity\ManagedBy $managedProperties
     * @return Owner
     */
    public function addManagedProperty(\AppBundle\Entity\ManagedBy $managedProperties)
    {
        $this->managedProperties[] = $managedProperties;

        return $this;
    }

    /**
     * Remove managedProperties
     *
     * @param \AppBundle\Entity\ManagedBy $managedProperties
     */
    public function removeManagedProperty(\AppBundle\Entity\ManagedBy $managedProperties)
    {
        $this->managedProperties->removeElement($managedProperties);
    }

    /**
     * Get managedProperties
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getManagedProperties()
    {
        return $this->managedProperties;
    }
}
