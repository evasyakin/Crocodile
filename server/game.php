<?php

// display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// register loader
include 'Loader.php';
Loader::registerDir(__DIR__.'/');
Loader::register();

session_start();

$game = &$_SESSION['game'];
$req = &$_GET;

if (empty($game)) {
    $rules = include 'rules.php';
    $game = new Crocodile\Game(new Crocodile\Rules($rules));
}

// routing
$com = &$req['command'];
switch ($com) {
    case 'start':
        $game->start();
        echo (bool) $game->isRun();
        break;

    case 'isRun':
        echo (bool) $game->isRun();
        break;

    case 'stop':
        $game->stop();
        echo (bool) !$game->isRun();
        break;

    case 'getCellsCount':
        echo $game->getCellsCount();
        break;

    case 'getOpened':
        echo $game->getMoves();
        break;

    case 'move':
        $cell = intval($req['cell']);
        $moves = $game->playerMove($cell);
        echo json_encode($moves);
        break;
    
    default:
        # code...
        break;
}