<?php

namespace ZendBricks\BricksCron\Job;

abstract class AbstractJob
{
    protected $id;
    protected $name;
    protected $data;
    protected $dependencies = [];
    protected $verboseMode;
    
    public function __construct($id, $name, array $data = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->data = $data;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
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
}
