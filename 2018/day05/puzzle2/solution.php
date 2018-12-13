<?php

$original_input = file_get_contents(__DIR__ . "/../input.txt");

$best = PHP_INT_MAX;
$letters = range('a','z');

foreach($letters as $letter) {
	echo $letter . ' ';
	$input = str_ireplace($letter, "", $original_input);
	for($i = 0; $i < strlen($input) - 1; $i++) {
		if($input[$i] != $input[$i + 1] && strtolower($input[$i]) == strtolower($input[$i + 1])) {
			$input         = str_split($input);
			$input[$i]     = false;
			$input[$i + 1] = false;
			$input         = array_filter($input);
			$input         = implode("", $input);
			$i             -= 2;
		}
	}
	echo strlen($input);
	if(strlen($input) < $best){
		$best = strlen($input);
		echo "*";
	}
	echo PHP_EOL;
}
echo $best.PHP_EOL;

