<?php

namespace AppBundle\Command;

use AppBundle\Entity\DeferredTask;
use AppBundle\Service\CronService;
use AppBundle\Service\TVarDumper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteCronCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('tlvflats:execute_cron_command')
            ->setDescription('Executes cron tasks');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get("doctrine.orm.entity_manager");
        $doctrine = $this->getContainer()->get('doctrine');


        /** @var CronService $cron */
        $cron = $this->get("cron");
        $cron->runAll();
    }

    protected function get($name)
    {
        return $this->getContainer()->get($name);
    }

    protected function has($name)
    {
        return $this->getContainer()->has($name);
    }
}
