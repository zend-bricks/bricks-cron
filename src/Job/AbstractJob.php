<?php

namespace ZendBricks\BricksCron\Job;

use Interop\Container\ContainerInterface;

abstract class AbstractJob
{
    const NAME = 'AbstractJob';
    protected $id;
    protected $dependencies;
    protected $container;
    protected $data;
    protected $verboseMode;
    
    public function __construct($id, array $dependencies, ContainerInterface $container, array $data = [])
    {
        $this->id = $id;
        $this->dependencies = $dependencies;
        $this->container = $container;
        $this->data = $data;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this::NAME;
    }
    
    public function getData($key = null)
    {
        if (!$key) {
            return $this->data;
        } elseif (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
    }
    
    /**
     * @return \ZendBricks\BricksCron\Resource\AbstractResource[]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }
    
    public function setVerboseMode($bool)
    {
        $this->verboseMode = $bool;
    }
    
    public function isVerboseMode()
    {
        return $this->verboseMode;
    }

    abstract public function run();
    
    /**
     * Get an object from the Interop Container
     * @param string $id
     * @return mixed
     */
    protected function getService($id)
    {
        return $this->container->get($id);
    }
}
