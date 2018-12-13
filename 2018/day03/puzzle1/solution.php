<?php

$input = file(__DIR__."/../input.txt");


$map = [];
$maxX = 0;
$maxY = 0;
foreach($input as $claim){
	$coords = parseClaim($claim);
	foreach($coords as $coord){
		$map[$coord[0]][$coord[1]]++;
		$maxX = max($coord[0],$maxX);
		$maxY = max($coord[1],$maxY);
	}
}

$overlaps = 0;

for($i=0;$i<$maxX;$i++){
	if(array_key_exists($i,$map)){
		for($j=0;$j<$maxY;$j++){
			if(array_key_exists($j,$map[$i]) && $map[$i][$j] > 1){
				$overlaps++;
			}
		}
	}
}

echo "Square Inches Overlapped: ".$overlaps.PHP_EOL;


function parseClaim($claim){
	$regex = '/#(\d+) @ (\d+),(\d+): (\d+)x(\d+)/';
	preg_match($regex,$claim,$m);

	$sx = $m[2];
	$sy = $m[3];
	$ex = $sx + $m[4];
	$ey = $sy + $m[5];
	$coords = [];
	for($i=$sx;$i<$ex;$i++){
		for($j=$sy;$j<$ey;$j++){
			$coords[] = [$i,$j];
		}
	}

	return $coords;
}