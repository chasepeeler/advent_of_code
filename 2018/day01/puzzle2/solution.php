<?php

$frequencies = file("../input.txt");
$foundFrequencies = [0];
$currentFrequency = 0;

$iterator = new InfiniteIterator(new ArrayIterator($frequencies));

foreach($iterator as $frequency){
	$currentFrequency += intval($frequency);
	if(in_array($currentFrequency,$foundFrequencies)){
		break;
	}
	$foundFrequencies[] = $currentFrequency;
}

echo "Final frequency is: {$currentFrequency}".PHP_EOL;