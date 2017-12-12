<?php

namespace Crocodile;

use Crocodile\RulesInterface;

class Rules implements RulesInterface
{

    public $cellsCount;

    public $prizes;

    public function __construct(array $config)
    {
        foreach ($config as $name => $value) {
            $this->$name = $value;
        }
    }

    public function getCellsCount()
    {
        return $this->cellsCount;
    }

    public function getPrizes()
    {
        return $this->prizes;
    }
}