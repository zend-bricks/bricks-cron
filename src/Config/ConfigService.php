<?php

namespace ZendBricks\BricksCron\Config;

class ConfigService
{
    protected $jobs = [];
    protected $resources = [];

    public function __construct(array $config) {
        if (array_key_exists('jobs', $config)) {
            $this->jobs = $config['jobs'];
        }
        if (array_key_exists('resources', $config)) {
            $this->resources = $config['resources'];
        }
    }
    
    public function getJobClass($jobName)
    {
        $job = $this->getJobData($jobName);
        if ($job && array_key_exists('class', $job)) {
            return $job['class'];
        }
    }
    
    public function getResourceClass($resourceName)
    {
        $resource = $this->getResourceData($resourceName);
        if ($resource && array_key_exists('class', $resource)) {
            return $resource['class'];
        }
    }
    
    public function getJobDependencies($jobName)
    {
        $job = $this->getJobData($jobName);
        if ($job && array_key_exists('dependencies', $job)) {
            $resources = [];
            foreach ($job['dependencies'] as $dependency) {
                $resourceClass = $this->getResourceClass($dependency);
                if (class_exists($resourceClass)) {
                    $resources[] = new $resourceClass();
                }
            }
            return $resources;
        }
    }

    public function getPossibleJobs(array $unavailableResources = [])
    {
        $possibleJobs = [];
        foreach ($this->jobs as $jobName => $job) {
            $possible = true;
            if (array_key_exists('dependencies', $job)) {
                foreach ($unavailableResources as $dependency) {
                    if (in_array($dependency, $job['dependencies'], true)) {
                        $possible = false;
                    }
                }
            }
            if ($possible) {
                $possibleJobs[] = $jobName;
            }
        }
        return $possibleJobs;
    }
    
    protected function getJobData($jobName)
    {
        if (array_key_exists($jobName, $this->jobs)) {
            return $this->jobs[$jobName];
        }
    }
    
    protected function getResourceData($resourceName)
    {
        if (array_key_exists($resourceName, $this->resources)) {
            return $this->resources[$resourceName];
        }
    }
}
