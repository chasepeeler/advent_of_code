<?php

$input = file(__DIR__."/../input.txt");

$initial_state = trim(str_replace("initial state: ","",$input[0]));
$rules = [];

for($i=2;$i<count($input);$i++){
	$r = explode(" => ",trim($input[$i]));

	$rules[$r[0]] = $r[1];
}



$current = str_split($initial_state);

$generations = [];
$generations[0] = $current;
$minPot = 0;
$maxPot = count($generations[0])-1;

for($i=0;$i<20;$i++){
	$minPot--;
	$maxPot++;
	$generations[0][$minPot] = ".";
	$generations[0][$maxPot] = ".";
}
ksort($generations[0]);

for($i=1;$i<=20;$i++){
	$generations[$i] = $generations[$i-1];
	foreach($generations[$i-1] as $pot=>$state){
		$generations[$i][$pot] = evolve($generations[$i-1],$pot,$rules);
	}
	print_generation($generations,$i);
}

echo "Sum: ".getSum($generations,20);


function getSum($generations,$i){

	$sum = 0;
	foreach($generations[$i] as $k => $v) {
		if($v == '#'){
			$sum += $k;
		}
	}
	return $sum;

}


function print_generation($generations,$i){
	echo "Generation {$i}: ";
	foreach($generations[$i] as $k=>$v){


		if($k == 0){
			echo "(";
		}
		echo $v;
		if($k == 0){
			echo ")";
		}
	}
	echo PHP_EOL;
}


function evolve($previousGeneration,$pot,$rules){
	$currentString = ($previousGeneration[$pot-2] ?? ".").($previousGeneration[$pot-1] ?? ".").$previousGeneration[$pot].($previousGeneration[$pot+1] ?? ".").($previousGeneration[$pot+2] ?? ".");

	foreach($rules as $rule=>$outcome){
		if($currentString == $rule){
			return $outcome;
		}
	}

	return ".";

}


