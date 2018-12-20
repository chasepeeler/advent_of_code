<?php

$input = file_get_contents(__DIR__."/../input.txt");
$numbers = preg_split('/\s+/',$input);


$metadataSum = 0;
processSubnodes($numbers);
echo $metadataSum;

function processSubnodes(&$numbers){
	global $metadataSum;
	$numSubnodes = array_shift($numbers);
	$numMetanodes = array_shift($numbers);
	for($i=0;$i<$numSubnodes;$i++){
		processSubnodes($numbers);
	}
	for($i=0;$i<$numMetanodes;$i++){
		$m = array_shift($numbers);
		$metadataSum += $m;
	}
}





