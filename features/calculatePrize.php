<?php
/**
* calcilate prize
*/

// display errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ---
// --- make range ---
// ---

// include rules
$rules = include 'rules.php';
$prizes = &$rules['prizes'];

// make range
$range = [];

$sum = 0;
foreach ($prizes as $prize) {
	$sum += $prize[1] * 100;
	$range[$sum] = $prize[0];
}

var_dump($range);

// ---
// --- return prize ---
// ---

echo '<hr>';

// thrown random
echo $rand = rand(1, 100);


// return prize
foreach ($range as $start => $prize) {
	if ($rand <= $start) {
		echo '- ' . $prize;
		break;
	}
}

