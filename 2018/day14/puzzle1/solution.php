<?php

$initial_recipe = str_split("37");// "327901";
$input = 327901;

$e1i = new InfiniteIterator(new ArrayIterator($initial_recipe));
$e1i->rewind();
$e2i = new InfiniteIterator(new ArrayIterator($initial_recipe));
$e2i->rewind();

moveIterator($e2i,1);

for($i=0;$i<($input+20);$i++){
	$e1current = $e1i->current();
	$e2current = $e2i->current();
	$newr = $e1current + $e2current;
	if($newr >= 10){
		addRecipe([$e1i,$e2i], 1);
	}
	addRecipe([$e1i, $e2i], $newr%10);

	moveIterator($e1i, 1+$e1current);
	moveIterator($e2i, 1+$e2current);

}
/** @var ArrayIterator $ai */
$ai = $e1i->getInnerIterator();
$a = $ai->getArrayCopy();

$a2 = array_slice($a, $input,10);
echo implode(" ",$a2).PHP_EOL;

/**
 * @param InfiniteIterator[] $iterators
 * @param int $recipe
 *
 * @return void
 */
function addRecipe($iterators,$recipe){
	foreach($iterators as $iterator){
		/** @var ArrayIterator $innerIterator */
		$innerIterator = $iterator->getInnerIterator();
		$innerIterator->append($recipe);
	}
}

/**
 * @param InfiniteIterator $i
 * @param int $steps
 *
 * @return void
 */
function moveIterator($i,$steps){
	for($j=0;$j<$steps;$j++){
		$i->next();
	}
}