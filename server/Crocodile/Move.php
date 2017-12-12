<?php

namespace Crocodile;

use Crocodile\Game;

class Move
{
    protected $_game;
    public $player;
    public $cell;
    public $prize;
    public $errorMessage;
    public $repeat = false;
    public $restart = false;

    public function __construct(Game $game, $selectedCell, string $player = 'bot')
    {
        $this->_game = &$game;
        $this->cell = $selectedCell;
        $this->player = $player;
        $selected = $this->_game->selectCell($this);
        if ($selected === true) {
            $this->prize = $this->_game->getPrize();
        }
    }

    public function __toString()
    {
        $data = $this;
        $data->_game = null;
        return json_encode($data);
    }
}