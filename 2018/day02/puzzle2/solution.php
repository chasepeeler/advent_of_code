<?php

$ids = file(__DIR__ . "/../input.txt");

foreach($ids as $id){
	foreach($ids as $id2){
		if(strlen($id) <> strlen($id2)){
			continue;
		}
		if($id == $id2){
			continue;
		}
		$diffs = 0;
		$common = "";
		for($i=0;$i<strlen($id);$i++){
			if($id[$i] != $id2[$i]){
				$diffs++;
			} else {
				$common .= $id[$i];
			}
			if($diffs > 1){
				continue 2;
			}
		}
		echo $common . PHP_EOL;
		exit;
	}
}