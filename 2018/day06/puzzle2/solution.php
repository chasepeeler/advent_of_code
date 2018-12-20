<?php
ini_set('memory_limit', '8G');
define('C', 0);
define('R', 1);
define('D', 1);
define('L', 0);
define('LOC1', 'AA');
define('MC', '()');

$input     = file(__DIR__ . "/../input.txt");
$locations = [];
$maxR      = $maxC = 0;
for($i = 0, $j = LOC1; $i < count($input); $i++, $j++) {
	$locations[$j] = explode(", ", $input[$i]);
	$maxC          = max($maxC, $locations[$j][C]);
	$maxR          = max($maxR, $locations[$j][R]);
}


$maxR += 500;
$maxC += 500;

$minR = 0;
$minC = 0;

$distances = [];
$points = 0;


for($r = $minR; $r <= $maxR; $r++) {
	for($c = $minC; $c <= $maxC; $c++) {
		$total_distance = 0;
		foreach($locations as $loc => $coord) {
			$total_distance += abs($r - $coord[R]) + abs($c - $coord[C]);
		}
		if($total_distance < 10000){
			$points++;
		}
	}
}

echo $points;
