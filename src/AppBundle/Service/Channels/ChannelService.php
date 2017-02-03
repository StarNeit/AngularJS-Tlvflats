<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 24/05/16
 * Time: 16:48
 */

namespace AppBundle\Service\Channels;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;

class ChannelService
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Registry
     */
    private $doctrine;

    private $services = array();

    /**
     * ChannelService constructor.
     */
    public function __construct(Logger $logger, Container $container, Registry $doctrine, $channelServices)
    {
        $this->logger = $logger;
        $this->doctrine = $doctrine;

        foreach ($channelServices as $channelService) {
            $this->services[] = $container->get($channelService);
        }
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }
}