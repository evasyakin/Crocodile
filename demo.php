<?php

// display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// register loader
include 'Loader.php';
Loader::registerDir(__DIR__.'/');
Loader::register();

?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <style>
        body {}
        table {border-collapse: collapse;}
        td {border: solid #aaa 1px; padding: 10px;}
    </style>

</head>
<body>

<?php


$rules = include 'rules.php';
$game = new Crocodile\Game(new Crocodile\Rules($rules));
$game->start();
echo '<br><b>Start Game</b><br>';

for ($i = 0; $i < 50; $i++) {
    $cell = rand(0, 9);
    $moves = $game->playerMove($cell);
    echo $moves[0];
    echo '<br>';
    echo $moves[1];
    echo '<hr>';
    if ($game->isRun() === false) {
        // $game->showCells();
        $game->start();
        echo '<br><b>Restart Game</b><br>';
    }
}

?>

</body>
</html>