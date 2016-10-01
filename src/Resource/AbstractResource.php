<?php

namespace ZendBricks\BricksCron\Resource;

abstract class AbstractResource
{
    protected $name;
    
    public function getName()
    {
        return $this->name;
    }

    abstract public function isAvailable();
}
