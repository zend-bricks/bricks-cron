<?php

namespace ZendBricks\BricksUser\Controller;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZendBricks\BricksCron\Controller\CronController;
use ZendBricks\BricksCron\Queue\Worker;

class CronControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CronController($container->get(Worker::class));
    }
}
