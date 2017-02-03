<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 20/05/16
 * Time: 15:45
 */

namespace AppBundle\Service;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Channel;
use AppBundle\Entity\ChannelProperty;
use AppBundle\Entity\ChannelRoom;
use AppBundle\Entity\OptionsTemplate;

interface ChannelServiceInterface
{
    const PULL = 1;
    const PUSH = 2;
    const GET_ROOMS_LIST = 4;
    const GET_BOOKINGS_LIST = 8;
    const GET_PRICES = 16;
    const EDIT_ROOMS = 32;
    const EDIT_BOOKINGS = 64;
    const EDIT_PRICES = 128;
    const SINGLE_ROOM_PER_PROPERTY=256;

    /** @return int */
    public function getCapabilities();

    /** @return OptionsTemplate */
    public function getOptionsTemplate(Channel $channel);

    /** @return array */
    public function getProperties(Channel $channel);

    /** @return array */
    public function getRooms(ChannelProperty $channelProperty);

    /** @return  array */
    public function getBookings(ChannelProperty $channelProperty, ChannelRoom $channelRoom, \DateTime $dateFrom, \DateTime $dateTo);

    /** @return array */
    public function getPrices(Channel $channel, ChannelProperty $channelProperty, ChannelRoom $channelRoom, \DateTime $dateFrom, \DateTime $dateTo);

    public function createProperty(ChannelProperty $channelProperty);

    public function createRoom(ChannelRoom $room);

    public function modifyProperty(ChannelProperty $channelProperty);

    public function modifyRoom(ChannelRoom $channelRoom);

    public function addBooking(Booking $booking);

    public function modifyBooking(Booking $booking);

    public function removeProperty(ChannelProperty $channelProperty);

    public function removeRoom(ChannelRoom $room);

    public function removeBooking(Booking $booking);

    /** @return Channel */
    public function getChannel();
    
    /** @return string */
    public function getName();
}