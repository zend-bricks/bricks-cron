<?php

namespace ZendBricks\BricksCron;

return [
    'router' => [
        'routes' => [
            'cronWorker' => [
                'options' => [
                    'route' => 'cron-worker [--verbose]',
                    'defaults' => [
                        'controller' => Controller\CronController::class,
                        'action' => 'worker'
                    ]
                ]
            ],
        ]
    ]
];