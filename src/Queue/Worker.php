<?php

namespace ZendBricks\BricksCron\Queue;

use ZendBricks\BricksCron\Api\CronApiInterface;
use ZendBricks\BricksCron\Resource\AbstractResource;

class Worker
{
    const MAX_EXECUTION_TIME = 50;
    protected $progressOutput = true;
    protected $api;
    protected $resources = [];
    protected $verboseMode;

    /**
     * @param CronApiInterface $api
     */
    public function __construct(CronApiInterface $api)
    {
        $this->api = $api;
    }
    
    public function setVerboseMode($bool)
    {
        $this->verboseMode = $bool;
    }
    
    public function isVerboseMode()
    {
        return $this->verboseMode;
    }
    
    public function run()
    {
        $startTimestamp = time();
        $counter = 0;
        /* @var $job \ZendBricks\BricksCron\Job\AbstractJob */
        while (
            ($runTime = time() - $startTimestamp) < self::MAX_EXECUTION_TIME
            && $job = $this->api->getNextJob()  //there are any jobs to do
        ) {
            if ($this->progressOutput) {
                echo "\rProcessing job #" . ++$counter . ' (' . $job->getName() . '), working queue since ' . $runTime . 's. ' . (self::MAX_EXECUTION_TIME - $runTime) . 's left';
            }
            foreach ($job->getDependencies() as $dependency) {
                if (!$this->checkResource($dependency)) {
                    $this->api->markJobFailed($job);
                    continue 2;
                }
            }
            $job->setVerboseMode($this->isVerboseMode());
            if ($job->run()) {
                $this->api->markJobDone($job);
            } else {
                $this->api->markJobFailed($job);
            }
        }
    }
    
    public function setProgressOutput($status)
    {
        $this->progressOutput = $status;
    }
    
    protected function checkResource(AbstractResource $resource)
    {
        if (array_key_exists($resource->getName(), $this->resources)) {
            return $this->resources[$resource->getName()];
        } elseif ($resource->isAvailable()) {
            $this->resources[$resource->getName()] = true;
            return true;
        } else {
            $this->resources[$resource->getName()] = false;
            return false;
        }
    }
}
