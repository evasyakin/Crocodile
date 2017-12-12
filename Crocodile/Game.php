<?php

namespace Crocodile;

use Crocodile\Rules;
use Crocodile\Move;

class Game
{
    protected $_rules;

    protected $_cells = [];

    protected $_range = [];

    protected $_run = false;

    public $moves = [];

    public function __construct(Rules $rules)
    {
        // set rules
        $this->_rules = &$rules;
        // make range
        $sum = 0;
        foreach ($this->_rules->getPrizes() as $prize) {
            $sum += $prize[1] * 100;
            $this->_range[$sum] = $prize[0];
        } 
    }

    public function getCellsCount()
    {
        return $this->_rules->getCellsCount();
    }

    public function start()
    {
        // make cells
        $this->_cells = [];
        $exitCellndex = rand(1, $this->_rules->getCellsCount()) - 1;
        $this->_cells[$exitCellndex] = 2;
        $this->moves = [];
        $this->_run = true;
    }

    public function stop()
    {
        $this->_run = false;
    }

    public function isRun()
    {
        return $this->_run;
    }


    public function selectCell(Move $move)
    {
        if ($move->cell < 0 || $move->cell > $this->_rules->getCellsCount() || !preg_match('/^\d$/', $move->cell)) {
            $move->errorMessage = 'incorrect';
            $move->repeat = true;
        }
        else if (!empty($this->_cells[$move->cell])) {
            if ($this->_cells[$move->cell] === 2) {
                $move->errorMessage = 'stop';
                $move->restart = true;
                $this->stop();
            }
            else {
                $move->errorMessage = 'exists';
                $move->repeat = true;
            }
        }
        else {
            $this->_cells[$move->cell] = 1;
            return true;
        }
    }

    public function getPrize()
    {
        // thrown random
        $rand = rand(1, 100);

        // return prize
        foreach ($this->_range as $start => $prize) {
            if ($rand <= $start) {
                return $prize;
            }
        }
    }


    public function playerMove($cell)
    {
        $userMove = new Move($this, $cell, 'user');
        if ($userMove->repeat === true || $userMove->restart === true) {
            $botMove = null;
        }
        else {
            $botMove = $this->botMove();
            // $this->showCells();
        }
        $this->moves[] = $userMove;
        $this->moves[] = $botMove;
        return [$userMove, $botMove];
    }

    public function botMove()
    {
        while (true) {
            $cell = rand(1, $this->_rules->getCellsCount()) - 1;
            $move = new Move($this, $cell);
            if ($move->repeat === true) {
                continue;
            }
            else {
                return $move;
            }
        }
    }

    public function getMoves()
    {
        return json_encode($this->moves);
    }

    public function showCells()
    {
        echo '<table><tr>';
        for ($i = 0; $i < $this->_rules->getCellsCount(); $i++) {
            $cell = $this->_cells[$i] ?? 0;
            echo '<td>' . $cell . '</td>';
        }
        echo '</tr></table>';
    }



    // public function playerMove($cell)
    // {
        // if ($this->isRun() !== true) return 'stop';
        // $selected = $this->_selectCell($cell);
        // if (!$selected) {
        //     $a = ['cell' => $cell, 'error' => $this->error];
        //     echo '<br>';
        //     var_dump($a);
        // }
        // $prize = $this->_getPrize();
        // $move = $this->moves[] = ['player' => 'user', 'cell' => $cell, 'prize' => $prize];
        // echo '<br>';
        // var_dump($move);
        // $this->botMove();
    // }

    // public function botMove()
    // {
        // if ($this->isRun() !== true) return 'stop';
        // $i = 0;
        // while (true) {
        //     $cell = rand(1, $this->_rules->getCellsCount()) - 1;
        //     $selected = $this->_selectCell($cell);
        //     if ($selected === true) {
        //         $prize = $this->_getPrize();
        //         $move = $this->moves[] = ['player' => 'bot', 'cell' => $cell, 'prize' => $prize];
        //         break;
        //     }
        //     else if ($this->isRun() !== true) {
        //         $move = $this->moves[] = ['player' => 'bot', 'cell' => $cell, 'restart' => 1];
        //         break;
        //     }
        //     else {
        //         echo 'BLED';
        //     }
        //     $i++; if ($i > 200) break;
        // }
        // echo '<br>';
        // var_dump($move);
    // }
}