<?php

namespace ZendBricks\BricksCron\Queue;

use ZendBricks\BricksCron\Api\CronApiInterface;
use ZendBricks\BricksCron\Resource\AbstractResource;
use ZendBricks\BricksCron\Config\ConfigService;
use Interop\Container\ContainerInterface;

class Worker
{
    const MAX_EXECUTION_TIME = 50;
    protected $progressOutput = true;
    protected $api;
    protected $config;
    protected $container;
    protected $resources = [];
    protected $unavailableResources = [];
    protected $intervalJobs;
    protected $verboseMode;

    /**
     * @param CronApiInterface $api
     */
    public function __construct(CronApiInterface $api, ConfigService $config, ContainerInterface $container)
    {
        $this->api = $api;
        $this->config = $config;
        $this->container = $container;
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
            && $job = $this->getNextJob()  //there are any jobs to do
        ) {
            if ($this->progressOutput) {
                echo "\rProcessing job #" . ++$counter . ' (' . $job->getName() . '), working queue since ' . $runTime . 's. Time left: ' . (self::MAX_EXECUTION_TIME - $runTime) . 's';
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
        echo PHP_EOL;
        $this->api->onQueueCompleted();
    }
    
    public function setProgressOutput($status)
    {
        $this->progressOutput = $status;
    }
    
    protected function getNextJob()
    {
        if ($this->intervalJobs === null) {
            $this->intervalJobs = $this->config->getIntervalJobs();
        }
        
        if (!empty($this->intervalJobs)) {
            $currentMinuteStamp = (int) floor(time() / 60);
            foreach ($this->intervalJobs as $jobName => $interval) {
                if ($currentMinuteStamp % $interval == 0) {
                    $jobClass = $this->config->getJobClass($jobName);
                    $job = new $jobClass(0, $this->config->getJobDependencies($jobName), $this->container);
                    unset($this->intervalJobs[$jobName]);
                    return $job;
                }
            }
        }
        
        return $this->api->getNextJob($this->unavailableResources);
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
            $this->unavailableResources[] = $resource->getName();
            return false;
        }
    }
}
