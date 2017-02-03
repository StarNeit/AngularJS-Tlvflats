<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 30/05/16
 * Time: 12:01
 */

namespace AppBundle\Service;


use AppBundle\Entity\CronLog;
use Composer\Repository\RepositoryManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\DateTime;

class CronService
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Container
     */
    private $service_container;
    /**
     * @var Registry
     */
    private $doctrine;

    private $tasks = array();

    /**
     * CronService constructor.
     */
    public function __construct(Logger $logger, Container $service_container, Registry $doctrine, $cronTasks)
    {
        $this->logger = $logger;
        $this->service_container = $service_container;
        $this->doctrine = $doctrine;

        foreach ($cronTasks as $cronTask) {
            if ($this->service_container->has($cronTask))
                $this->tasks[] = $this->service_container->get($cronTask);
        }
    }

    public function runAll()
    {
        /** @var CronInterface $task */
        foreach ($this->tasks as $task) {
            $commandName = (new \ReflectionClass($task))->getShortName();
            $cronLogs = $this->doctrine->getRepository("AppBundle:CronLog")->findBy(array(
                "commandName" => $commandName
            ));

            if(empty($cronLogs)) {
                $cronLog = new CronLog();
                $cronLog ->setCommandName($commandName);
            } else {
                $cronLog = $cronLogs[0];
            }

            if($this->needToRun($task, $cronLog)) {
                try {
                    $task->run();
                } catch (\Exception $ex) {
                    $this->logger->error("Failed to complete ".$commandName, array('exception' => $ex));
                }

                $cronLog->setLastRun(new \DateTime());
                $this->doctrine->getEntityManager()->persist($cronLog);
                $this->doctrine->getEntityManager()->flush($cronLog);
            }
        }
    }

    private function needToRun(CronInterface $task, CronLog $cronLog)
    {
        if(empty($cronLog->getLastRun()))
            return true;
        
        return (new DateTime())>($cronLog->getLastRun()->add(new \DateInterval($task->spec())));
    }
}