<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 10:54 PM
 */
namespace cbs\tp6curd\strategy;

use cbs\tp6curd\template\IAutoMake;

class AutoMakeStrategy
{
    protected $strategy;

    public function Context(IAutoMake $obj)
    {
        $this->strategy = $obj;
    }

    public function executeStrategy($flag, $path, $other, $force)
    {
        $this->strategy->check($flag, $path, $force);
        $this->strategy->make($flag, $path, $other);
    }
}