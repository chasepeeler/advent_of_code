<?php

$frequencies = file("../input.txt");
$currentFrequency = 0;

foreach($frequencies as $frequency){
	$frequency = intval($frequency);
	$currentFrequency += $frequency;
}

echo "Final frequency is: {$currentFrequency}".PHP_EOL;