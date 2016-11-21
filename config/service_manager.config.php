<?php

use ZendBricks\BricksCron\Queue\Worker;
use ZendBricks\BricksCron\Queue\WorkerFactory;
use ZendBricks\BricksCron\Config\ConfigService;
use ZendBricks\BricksCron\Config\ConfigServiceFactory;

return [
    'factories' => [
        Worker::class => WorkerFactory::class,
        ConfigService::class => ConfigServiceFactory::class,
    ]
];
