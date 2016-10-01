<?php

namespace ZendBricks\BricksCron\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use ZendBricks\BricksCron\Queue\Worker;

class CronController extends AbstractActionController
{
    protected $worker;

    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
    }
    
    public function workerAction()
    {
        $this->worker->run();
    }
}
