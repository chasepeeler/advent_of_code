<?php

$input = file(__DIR__ . "/../input.txt");


$map  = [];
$maxX = 0;
$maxY = 0;
foreach($input as $claim) {
	$coords = parseClaim($claim);
	foreach($coords as $coord) {
		$map[$coord[0]][$coord[1]]++;
		$maxX = max($coord[0], $maxX);
		$maxY = max($coord[1], $maxY);
	}
}

foreach($input as $claim){
	$coords = parseClaim($claim);
	foreach($coords as $coord){
		if($map[$coord[0]][$coord[1]] != 1){
			continue 2;
		}
	}
	echo "Non overlapping claim is ".$coords[0][2];
	exit;
}


for($i = 0; $i < $maxX; $i++) {
	if(array_key_exists($i, $map)) {
		for($j = 0; $j < $maxY; $j++) {
			if(array_key_exists($j, $map[$i]) && $map[$i][$j] > 1) {
				$overlaps++;
			}
		}
	}
}




function parseClaim($claim)
{
	$regex = '/#(\d+) @ (\d+),(\d+): (\d+)x(\d+)/';
	preg_match($regex, $claim, $m);

	$sx     = $m[2];
	$sy     = $m[3];
	$ex     = $sx + $m[4];
	$ey     = $sy + $m[5];
	$coords = [];
	for($i = $sx; $i < $ex; $i++) {
		for($j = $sy; $j < $ey; $j++) {
			$coords[] = [$i, $j, $m[1]];
		}
	}

	return $coords;
}