<?php

namespace ZendBricks\BricksCron;

class Module
{
    const VERSION = '1.0.0';

    public function getConfig()
    {
        return require __DIR__ . '/../config/module.config.php';
    }
    
    public function getConsoleUsage($console){
        return [
            'cron-worker' => 'Start a worker for cron queue',
        ];
    }
}
