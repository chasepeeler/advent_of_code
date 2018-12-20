<?php
error_reporting(E_ALL);
ini_set("display_errors","on");
ini_set("memory_limit","8G");
$players = 447;
$high    = 7151000;

$currentMarble                 = 0;
$marbles[0] = ["next"=>0,"previous"=>0];
$scores                        = [];
$player                        = 1;

for($i=1;$i<=$players;$i++){
	$scores[$i] = 0;
}

for($i = 1; $i <= $high; $i++) {

	if($i % 23 == 0) {
		$scores[$player] += $i;

		$rMarble = getPreviousMarble($currentMarble);
		$rMarble = getPreviousMarble($rMarble);
		$rMarble = getPreviousMarble($rMarble);
		$rMarble = getPreviousMarble($rMarble);
		$rMarble = getPreviousMarble($rMarble);
		$rMarble = getPreviousMarble($rMarble);
		$rMarble = getPreviousMarble($rMarble);

		$scores[$player] += $rMarble;
		$currentMarble   = removeMarble($rMarble);
	} else {
		insertMarble($i, $currentMarble);
		$currentMarble = $i;
	}


	$player++;
	if($player > $players) {
		$player = 1;
	}
	//echo "Turn {$i}: ".printCircle($marbles,$currentMarble).PHP_EOL;

}

asort($scores);
echo "High Score: ".array_reverse(array_values($scores))[0];


function getPreviousMarble($marble){
	global $marbles;

	return $marbles[$marble]['previous'];


}


function printCircle($marbles,$c){
	$currentMarble = 0;
	$buffer = "";
	do {
		if($c == $currentMarble){
			$buffer .= "(";
		}
		$buffer .= $currentMarble;
		if($c == $currentMarble) {
			$buffer .= ")";
		}
		$buffer .= " ";
		$currentMarble = $marbles[$currentMarble]['next'];
	}	while($currentMarble != 0);
	return $buffer;
}

/**
 * @param int $marble
 * @param int $currentMarble
 *
 * @return void
 */
function insertMarble($marble, $currentMarble)
{
	global $marbles;
	$marble1 = $marbles[$currentMarble]['next'];
	$marble2 = $marbles[$marble1]['next'];


	insertMarbleBetween($marble, $marble1, $marble2);
}


/**
 * @param int $marble
 * @param int $marble1
 * @param int $marble2
 *
 * @return void
 */
function insertMarbleBetween($marble, $marble1, $marble2)
{
	global $marbles;

	$newMarble = ["next"=>$marble2,"previous"=>$marble1];
	$marbles[$marble1]['next'] = $marble;
	$marbles[$marble2]['previous'] = $marble;

	$marbles[$marble] = $newMarble;

}

/**
 * @param int $marble
 *
 * @return int
 */
function removeMarble($marble)
{
	global $marbles;

	$prevMarble = $marbles[$marble]['previous'];
	$nextMarble = $marbles[$marble]['next'];

	$marbles[$prevMarble]['next'] = $nextMarble;
	$marbles[$nextMarble]['previous'] = $prevMarble;

	unset($marbles[$marble]);
	return $nextMarble;

}

