<?php

namespace ZendBricks\BricksCron\Queue;

abstract class AbstractJob
{
    protected $id;
    protected $name;
    protected $dependencies = [];

    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return \ZendBricks\BricksCron\Resource\AbstractResource[]
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    abstract function run();
}
