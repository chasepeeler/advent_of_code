<?php


$serialNumber = $argv[1] ?? 8979;


$cells = [];

for($x=1;$x<=300;$x++){
	for($y=1;$y<=300;$y++){
		$cells[$x][$y] = calculatePower($x,$y,$serialNumber);
	}
}

$maxPower = -PHP_INT_MAX;
$maxLocation = "";

for($x=1;$x<=298;$x++){
	for($y=1;$y<=298;$y++){
		$p = getTotalPower($cells, $x, $y);
		if($p > $maxPower){
			$maxPower = $p;
			$maxLocation = ($x).','.($y);
		}
	}
}

echo "{$maxLocation} with a power of {$maxPower}";


function getTotalPower($cells,$x,$y){
	$total = 0;
	for($xp=$x;$xp<=$x+2;$xp++){
		for($yp=$y;$yp<=$y+2;$yp++){
			$total += $cells[$xp][$yp];
		}
	}
	return $total;
}

function calculatePower($x,$y,$serialNumber){
	$rackId = $x + 10;
	$power = $rackId * $y;
	$power += $serialNumber;
	$power *= $rackId;
	if($power < 100){
		$power = 0;
	} else {
		$power = substr($power,-3,1);
	}
	$power -= 5;

	return $power;
}