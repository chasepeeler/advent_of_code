<?php

$ids = file(__DIR__."/../input.txt");
$three = 0;
$two = 0;
foreach($ids as $id){
	$char_count = array_unique(array_values(count_chars($id)));
	$three += intval(in_array(3,$char_count));
	$two += intval(in_array(2, $char_count));

}

echo "Checksum: ".($three*$two).PHP_EOL;
