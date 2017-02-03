<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/02/16
 * Time: 14:02
 */

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\SerializedName;

use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PlaceRepository")
 * @ORM\Table(name="place")
 *
 * @ExclusionPolicy("all")
 */
class Place
{
    const COUNTRY=0;
    const REGION=1;
    const CITY=2;
    const DISTRICT=3;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    /**
     * @ORM\Column(type="text")
     *
     * @Expose
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Place", mappedBy="parent", orphanRemoval=false)
     *
     */
    protected $children;

    /**
     * @Expose
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @Expose
     * @ORM\Column(type="boolean", options={"default" = true})
     */
    protected $searchable=true;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Place
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
     * Set type
     *
     * @param integer $type
     * @return Place
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Place $parent
     * @return Place
     */
    public function setParent(\AppBundle\Entity\Place $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Place 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get parentId
     *
     * @return int|null
     */
    public function getParentId()
    {
        return $this->parent===null?null:$this->getParent()->getId();
    }

    /** get full name
     *
     * @VirtualProperty
     * @SerializedName("full_name")
     * @return string
     */
    public function getFullName() {
        $res = $this->getName();
        $current=$this;
        $iter = 0;
        while(!is_null($current->getParent()) && $iter<10) {
            $current = $current ->getParent();
            $res = $res.", ".$current->getName();
            $iter++;
        }

        return $res;
    }

    /** get type name
     *
     * @VirtualProperty
     * @SerializedName("type_name")
     * @return string
     */
    public function getTypeName() {
        $refl = new ReflectionClass($this);
        $consts = $refl->getConstants();
        foreach($consts as $const=>$value) {
            if($value == $this->getType())
                return $const;
        }

        return $this->getType();
    }

    /**
     * Add children
     *
     * @param \AppBundle\Entity\Place $children
     * @return Place
     */
    public function addChild(\AppBundle\Entity\Place $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \AppBundle\Entity\Place $children
     */
    public function removeChild(\AppBundle\Entity\Place $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set searchable
     *
     * @param boolean $searchable
     * @return Place
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Get searchable
     *
     * @return boolean 
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    public function getAllChildren($showAll = false) {
        $res = array();
        foreach ($this->getChildren() as $place) {
            if ($showAll || $place->getSearchable()) {
                array_push($res, $place);
                $res = array_merge($res, $this->getAllChildren($showAll));
            }
        }

        return $res;
    }

    public function getAllParents()
    {
        $res = array();
        $current = $this->getParent();
        while(!is_null($current)) {
            array_push($res, $current);
            $current = $current->getParent();
        }

        return $res;
    }
}
