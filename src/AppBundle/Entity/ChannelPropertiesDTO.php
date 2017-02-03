<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 23/05/16
 * Time: 15:30
 */

namespace AppBundle\Entity;


use JsonSerializable;

class ChannelPropertiesDTO implements JsonSerializable
{
    /** @var  array */
    protected $channelProperties;
    
    /**
     * @return array
     */
    public function getChannelProperties()
    {
        return $this->channelProperties;
    }

    /**
     * @param array $channelProperties
     * @return ChannelPropertiesDTO
     */
    public function setChannelProperties($channelProperties)
    {
        $this->channelProperties = $channelProperties;
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
        return $this->channelProperties;
    }
}