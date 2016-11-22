<?php

namespace ZendBricks\BricksCron\Queue;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use ZendBricks\BricksCron\Api\CronApiInterface;
use ZendBricks\BricksCron\Config\ConfigService;

class WorkerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $api = $container->get(CronApiInterface::class);
        $config = $container->get(ConfigService::class);
        return new Worker($api, $config, $container);
    }
}
