<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 23/05/16
 * Time: 15:30
 */

namespace AppBundle\Entity;


use JsonSerializable;

class ChannelPropertyDTO implements JsonSerializable
{
    /** @var  int */
    protected $propertyId;
    /** @var  string */
    protected $title;
    /** @var  string */
    protected $address;
    /** @var  string */
    protected $channelId;

    /**
     * @return int
     */
    public function getPropertyId()
    {
        return $this->propertyId;
    }

    /**
     * @param int $propertyId
     */
    public function setPropertyId($propertyId)
    {
        $this->propertyId = $propertyId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * @param string $channelId
     */
    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;
    }

    public function init(Property $property, ChannelProperty $channelProperty = null)
    {
        $this->address = $property->getAddress();
        if (isset($channelProperty))
            $this->channelId = $channelProperty->getChannelId();
        else
            $this->channelId = null;
        $this->propertyId = $property->getId();
        $this->title = $property->getTitle();

        return $this;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            "propertyId"=>$this->getPropertyId(),
            "channelId" => $this->getChannelId()
        ];
    }
}