<?php
ini_set("memory_limit","8G");
$initial_recipe = [3,7];
$input          = $argv[1] ?? "327901";

$recipes = $initial_recipe;
$elf1 = 0;
$elf2 = 1;

$inputArr = array_map("intval",str_split($input));
$inputLen = count($inputArr);
$endArray = $recipes;

$tick = 0;
$length =count($recipes);
while(true){
	$tick++;
	$elf1current = $recipes[$elf1];
	$elf2current = $recipes[$elf2];

	$newRecipe      = $elf1current + $elf2current;

	if($newRecipe >= 10) {
		array_push($recipes,1);
		$length++;
		check(1, $inputArr, $inputLen, $recipes, $length, $input);
	}


	$newScore = $newRecipe % 10;
	array_push($recipes,$newScore);
	$length++;
	check($newScore, $inputArr, $inputLen, $recipes, $length, $input);

	$numRecipes = count($recipes);
	$elf1 = moveElf($elf1,$elf1current,$numRecipes);
	$elf2 = moveElf($elf2,$elf2current,$numRecipes);


}

function check($newScore,$inputArr,$inputLen,$recipes,$length,$input){
	if($newScore == $inputArr[$inputLen - 1]) {
		$match = true;
		for($i = 0, $j = $inputLen; $i < $inputLen; $i++, $j--) {
			if($inputArr[$i] != $recipes[$length - $j]) {
				$match = false;
				break;
			}
		}
		if($match) {
			echo "{$input} appears after " . (count($recipes) - $inputLen) . " recipes." . PHP_EOL;
			exit();
		}
	}
	return;
}


function moveElf($elf,$currentRecipe,$numRecipes){
	$steps = 1 + $currentRecipe;

	$j = $elf;
	for($i=0;$i<$steps;$i++){
		$j++;
		if($j>= $numRecipes){
			$j = 0;
		}
	}

	return $j;

}

