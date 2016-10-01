<?php

namespace ZendBricks\BricksCron\Queue;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZendBricks\BricksCron\Api\CronApiInterface;

class WorkerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $api = $container->get(CronApiInterface::class);
        return new Worker($api);
    }
}
