<?php

$input = file(__DIR__ . "/../input.txt");

$initial_state = trim(str_replace("initial state: ", "", $input[0]));
$rules         = [];

for($i = 2; $i < count($input); $i++) {
	$r = explode(" => ", trim($input[$i]));
	$rules[$r[0]] = $r[1];
}


$current_generation = str_split($initial_state);
$pots_with_plants = [];
for($i=0;$i<count($current_generation);$i++){
	if($current_generation[$i] == "#"){
		$pots_with_plants[] = $i;
	}
}

define("GENS", 50000000000);

$minPot         = -2;
$maxPot = count($current_generation)+2;

$newSum = $prevSum = array_sum($pots_with_plants);
for($i = 1; $i <= GENS; $i++) {
	$next_pots_with_plants = [];
	for($j=$minPot;$j<=$maxPot;$j++){
		if(will_have_plant($pots_with_plants,$j,$rules)){
			$next_pots_with_plants[] = $j;
		}
	}
	$pots_with_plants = $next_pots_with_plants;
	$minPot -= 2;
	$maxPot += 2;

	$prevSum = $newSum;
	$newSum = array_sum($pots_with_plants);

	echo "After Generation {$i}: ".$newSum." (".($newSum-$prevSum).")".PHP_EOL;
}

echo "Sum: " . array_sum($pots_with_plants);


function will_have_plant($pots_with_plants, $pot, $rules)
{
	$s = "";
	for($i=$pot-2;$i<=$pot+2;$i++){
		$s .= in_array($i,$pots_with_plants) ? "#" : ".";
	}

	foreach($rules as $rule => $outcome) {
		if($s == $rule) {
			return $outcome == "#";
		}
	}

	return false;

}


