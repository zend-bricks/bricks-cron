<?php

namespace ZendBricks\BricksCron;

return [
    'router' => [
        'routes' => [
            'cronWorker' => [
                'options' => [
                    'route'    => 'cron-worker',
                    'defaults' => [
                        'controller' => Controller\CronController::class,
                        'action' => 'worker'
                    ]
                ]
            ],
        ]
    ]
];