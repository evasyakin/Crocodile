<?php
/**
* select cell
*/

// display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ---
// --- make cells ---
// ---

// include rules
$rules = include 'rules.php';
$cellsCount = &$rules['cellsCount'];

// make cells
$cells = [];
$cells = [0,0,0,0,0,0,0,0,0,0];
$cells = [0,0,1,1,0,0,0,0,0,0];
$exitCellndex = rand(0, $cellsCount);
$cells[$exitCellndex] = 2;


var_dump($cells);

// ---
// --- select cell ---
// ---
echo '<hr>';

$selected = &$_GET['s'];
if (!isset($selected)) die ('please, select cell');

// check cells range
if ($selected < 0 || $selected > $cellsCount || !preg_match('/^\d$/', $selected)) die ('incorrect cell');

if (!empty($cells[$selected])) {
	if ($cells[$selected] === 2) die ('end game');
	else die ('cell already exists');
}

$cells[$selected] = 1;

var_dump($cells);