<?php

namespace AppBundle\Command;

use AppBundle\Entity\DeferredTask;
use AppBundle\Service\TVarDumper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteDeferredCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('tlvflats:execute_deferred_command')
            ->setDescription('Executes deferred tasks');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get("doctrine.orm.entity_manager");
        $doctrine = $this->getContainer()->get('doctrine');

        $tasks = $em->createQuery("select df " .
            "from AppBundle:DeferredTask df " .
            "where df.finishedAt is null " .
            "  and df.executeAt<CURRENT_TIMESTAMP() order by df.executeAt")->execute();

        $output->writeln("Tasks: " . count($tasks));
        /** @var DeferredTask $task */
        foreach ($tasks as $task) {
            $output->writeln("Executing " . $task->getExecuteCommand() . " for (" . $task->getParams() . ")");
            list($servicename, $command) = preg_split("/:+/", $task->getExecuteCommand());
            try {
                $service = $this->get($servicename);
                $params=preg_split("/,/", $task->getParams());
                $actualParams=array();
                foreach($params as $param) {
                    $matches=array();
                    if(preg_match("/(.*?)\\((\\d+)\\)/", $param, $matches)) {
                        $entityName = $matches[1];
                        $entityId = $matches[2];
                        array_push($actualParams, $doctrine->getRepository(
                            strpos($entityName, ":")===false?"AppBundle:".$entityName:$entityName
                        )->find($entityId));
                    } else {
                        array_push($actualParams, $param);
                    }
                }
                $output->writeln("Going to run ".get_class($service)." service with command ".$command." params: ");
                foreach($actualParams as $aParam) {
                    if(is_object($aParam)) {
                        $output->writeln("  ".get_class($aParam).": ".$aParam->getId());
                    } else {
                        $output->writeln("  ".$aParam);
                    }
                }
                $item = call_user_func_array(array($service, $command), $actualParams);
                if(
                    ( !is_array( $item ) ) &&
                    ( ( !is_object( $item ) && settype( $item, 'string' ) !== false ) ||
                        ( is_object( $item ) && method_exists( $item, '__toString' ) ) )
                ) {

                    $task->setLog($item);
                } else {
                    $task->setLog($this->get("vardump")->dump($item));
                }
                $task->setResult("OK");
                $output->writeln($task->getLog());
            } catch (\Exception $ex) {
                $task->setResult("FAILED");
                $task->setLog($ex->getMessage());
                $output->writeln($ex->getMessage());
            }
            $task->setFinishedAt(new \DateTime());
            $em->persist($task);
        }
        $em->flush();
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
