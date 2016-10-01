<?php

namespace ZendBricks\BricksCron\Api;

use ZendBricks\BricksCron\Queue\AbstractJob;

interface CronApiInterface
{
    /**
     * @param array $unavailableResources
     * @return AbstractJob
     */
    public function getNextJob(array $unavailableResources = []);
    
    public function markJobDone(AbstractJob $job);
    
    public function markJobFailed(AbstractJob $job);
}