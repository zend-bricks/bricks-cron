<?php

use ZendBricks\BricksCron\Queue\Worker;
use ZendBricks\BricksCron\Queue\WorkerFactory;

return [
    'factories' => [
        Worker::class => WorkerFactory::class,
    ]
];
