<?php

$input = file_get_contents(__DIR__."/../input.txt");

$j = 0;
for($i=0;$i<strlen($input)-1;$i++){
	if($input[$i] != $input[$i+1] && strtolower($input[$i]) == strtolower($input[$i+1])){
		$input = str_split($input);
		$input[$i] = false;
		$input[$i+1] = false;
		$input = array_filter($input);
		$input = implode("",$input);
		$i -= 2;
		echo strlen($input).PHP_EOL;
	}
}

echo strlen($input).PHP_EOL;

