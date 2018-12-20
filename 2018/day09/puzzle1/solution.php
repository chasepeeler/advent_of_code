<?php
ini_set("memory_limit","8G");
$players = 447;
$high = 71510;

$currentMarble = new Marble();
$currentMarble->number = 0;
$currentMarble->nextMarble = $currentMarble;
$currentMarble->previousMarble = $currentMarble;
$scores = [];
$player = 1;
for($i=1;$i<=$high;$i++){

	$newMarble = new Marble();
	$newMarble->number = $i;

	if($i%23 == 0){
		$scores[$player] += $i;
		$rMarble = $currentMarble->previousMarble->previousMarble->previousMarble->previousMarble->previousMarble->previousMarble->previousMarble;
		$scores[$player] += $rMarble->number;
		$currentMarble = removeMarble($rMarble);
	} else {
		insertMarble($newMarble, $currentMarble);
		$currentMarble = $newMarble;
	}



	$player++;
	if($player > $players){
		$player = 1;
	}
}

asort($scores);
echo "High Score: " . array_reverse(array_values($scores))[0];



class Marble {
	public $number;

	/**
	 * @var Marble
	 */
	public $nextMarble;

	/**
	 * @var Marble
	 */
	public $previousMarble;

}

/**
 * @param Marble $marble
 * @param Marble $currentMarble
 *
 * @return void
 */
function insertMarble($marble,$currentMarble){
	$marble1 = $currentMarble->nextMarble;
	$marble2 = $marble1->nextMarble;
	insertMarbleBetween($marble, $marble1, $marble2);
}


/**
 * @param Marble $marble
 * @param Marble $marble1
 * @param Marble $marble2
 *
 * @return void
 */
function insertMarbleBetween($marble,$marble1,$marble2){
		$marble->nextMarble = $marble2;
		$marble2->previousMarble = $marble;

		$marble1->nextMarble = $marble;
		$marble->previousMarble = $marble1;
}

/**
 * @param Marble $marble
 *
 * @return Marble
 */
function removeMarble($marble){

	$marble->previousMarble->nextMarble = $marble->nextMarble;
	$marble->nextMarble->previousMarble = $marble->previousMarble;
	$nextCurrent = $marble->nextMarble;
	$marble->nextMarble = null;
	$marble->previousMarble = null;

	return $nextCurrent;

}

