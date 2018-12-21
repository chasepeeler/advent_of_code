<?php

define('TURN_LEFT', 1);
define('TURN_RIGHT', 2);
define('TURN_STRAIGHT', 3);

define('DIR_LEFT', '<');
define('DIR_RIGHT', '>');
define('DIR_UP', '^');
define('DIR_DOWN', 'v');
define('DIR_DEAD', 'x');

define('TRACK_STRAIGHT_UP_DOWN', '|');
define('TRACK_STRAIGHT_LEFT_RIGHT', '-');
define('TRACK_CURVE_UR_LD', '/');
define('TRACK_CURVE_UL_RD', '\\');
define('TRACK_INTERSECTION', '+');


$dirToTrackMap = [DIR_LEFT => TRACK_STRAIGHT_LEFT_RIGHT, DIR_RIGHT => TRACK_STRAIGHT_LEFT_RIGHT, DIR_UP => TRACK_STRAIGHT_UP_DOWN, DIR_DOWN => TRACK_STRAIGHT_UP_DOWN];

$turnMap                        = [];
$turnMap[DIR_LEFT][TURN_LEFT]   = DIR_DOWN;
$turnMap[DIR_LEFT][TURN_RIGHT]  = DIR_UP;
$turnMap[DIR_UP][TURN_LEFT]     = DIR_LEFT;
$turnMap[DIR_UP][TURN_RIGHT]    = DIR_RIGHT;
$turnMap[DIR_RIGHT][TURN_LEFT]  = DIR_UP;
$turnMap[DIR_RIGHT][TURN_RIGHT] = DIR_DOWN;
$turnMap[DIR_DOWN][TURN_LEFT]   = DIR_RIGHT;
$turnMap[DIR_DOWN][TURN_RIGHT]  = DIR_LEFT;

$curveMap                               = [];
$curveMap[TRACK_CURVE_UL_RD]            = [];
$curveMap[TRACK_CURVE_UL_RD][DIR_UP]    = DIR_LEFT;
$curveMap[TRACK_CURVE_UL_RD][DIR_LEFT]  = DIR_UP;
$curveMap[TRACK_CURVE_UL_RD][DIR_RIGHT] = DIR_DOWN;
$curveMap[TRACK_CURVE_UL_RD][DIR_DOWN]  = DIR_RIGHT;
$curveMap[TRACK_CURVE_UR_LD]            = [];
$curveMap[TRACK_CURVE_UR_LD][DIR_UP]    = DIR_RIGHT;
$curveMap[TRACK_CURVE_UR_LD][DIR_RIGHT] = DIR_UP;
$curveMap[TRACK_CURVE_UR_LD][DIR_LEFT]  = DIR_DOWN;
$curveMap[TRACK_CURVE_UR_LD][DIR_DOWN]  = DIR_LEFT;

$input = file(__DIR__ . "/../input.txt");

$input = array_map("rtrim", $input);
$maxC  = max(array_map('strlen', $input));
$maxR  = count($input);

$cars      = [];
$carIndex  = 0;
$carsInRow = [];
$track     = [];
for($r = 0; $r < $maxR; $r++) {
	for($c = 0; $c < $maxC; $c++) {
		$track[$r][$c] = $input[$r][$c];
		if(in_array($input[$r][$c], [DIR_LEFT, DIR_RIGHT, DIR_DOWN, DIR_UP])) {
			$car             = new Car($r, $c, $input[$r][$c]);
			$track[$r][$c]   = $dirToTrackMap[$input[$r][$c]];
			$cars[]          = $car;
			$carsInRow[$r][] = $car;
		}
	}
}

$tick = 0;
//printTrack($track, $cars);
//echo "----------------------" . PHP_EOL;
$carsAlive = $cars;
do {
	$tick++;
	$newCarsInRow = [];
	for($r = 0; $r < $maxR; $r++) {
		$carsInRow[$r] = array_filter($carsInRow[$r],function($a){
			return !$a->collided;
		});
		usort(
			$carsInRow[$r],
			function($a, $b) {
				return $a->col <=> $b->col;
			}
		);
		foreach($carsInRow[$r] as $car) {
			/** @var Car $car */
			$car->move($track);
			$newCarsInRow[$car->row][] = $car;
			foreach($carsAlive as $otherCar) {
				if(!$otherCar->collided) {
					if($car->checkForCollision($otherCar)) {
						echo "{$car->id} collided with {$otherCar->id} at {$car->col},{$car->row} during tick {$tick}" . PHP_EOL;
						break;
					}
				}
			}
		}
	}
	$carsAlive = array_filter(
		$cars,
		function($c) {
			return !$c->collided;
		}
	);
	if($tick % 100 == 0) {
		echo count($carsAlive) . ' out of ' . count($cars) . ' still alive after tick ' . $tick . PHP_EOL;
	}
	//	printTrack($track,$cars);
	//	echo "----------------------".PHP_EOL;

	$carsInRow = $newCarsInRow;
} while(count($carsAlive) != 1);
$carsAlive = array_values($carsAlive);
echo "The last car is at {$carsAlive[0]->col},{$carsAlive[0]->row}" . PHP_EOL;

/**
 * @param array $track
 * @param Car[] $cars
 *
 * @return void
 */
function printTrack($track, $cars = [], $startRow = 0, $endRow = null, $startCol = 0, $endCol = null)
{
	foreach($cars as $car) {
		$track[$car->row][$car->col] = $car->dir;
	}

	$endRow = $endRow ?? count($track) - 1;
	$endRow++;

	for($r = $startRow; $r < $endRow; $r++) {
		$_endCol = $endCol ?? count($track) - 1;
		$_endCol++;
		for($c = $startCol; $c < $_endCol; $c++) {
			echo $track[$r][$c];
		}
		echo PHP_EOL;
	}
}

class Car
{

	protected static $nextId = 0;

	public $id;

	public $row;

	public $col;

	/** @var InfiniteIterator */
	public $turns;

	public $dir;

	public $collided = false;


	public function __construct($row, $col, $dir)
	{
		$this->id = self::$nextId;
		self::$nextId++;
		$this->row   = $row;
		$this->col   = $col;
		$this->turns = new InfiniteIterator(new ArrayIterator([TURN_LEFT, TURN_STRAIGHT, TURN_RIGHT]));
		$this->turns->rewind();
		$this->dir = $dir;
	}


	protected function turn($turnDirection)
	{
		global $turnMap;

		if($turnDirection == TURN_STRAIGHT) {
			return;
		}
		$this->dir = $turnMap[$this->dir][$turnDirection];

	}

	public function getNextTurn()
	{
		$c = $this->turns->current();
		$this->turns->next();

		return $c;
	}

	public function move($track)
	{
		if(!$this->collided) {
			if($this->dir == DIR_LEFT) {
				$this->col--;
			} elseif($this->dir == DIR_RIGHT) {
				$this->col++;
			} elseif($this->dir == DIR_UP) {
				$this->row--;
			} else {
				$this->row++;
			}

			$nextTrack = $track[$this->row][$this->col];

			if($nextTrack == TRACK_INTERSECTION) {
				$turnDirection = $this->getNextTurn();
				$this->turn($turnDirection);
			} elseif($nextTrack == TRACK_CURVE_UL_RD || $nextTrack == TRACK_CURVE_UR_LD) {
				global $curveMap;
				$this->dir = $curveMap[$nextTrack][$this->dir];
			}

		}
	}

	/**
	 * @param Car $car
	 *
	 * @return boolean
	 */
	public function checkForCollision($car)
	{
		if($this->id != $car->id && $car->row == $this->row && $car->col == $this->col) {
			$this->collided = true;
			$car->collided  = true;
			$this->dir      = DIR_DEAD;
			$car->dir       = DIR_DEAD;
		}

		return $this->collided;
	}

}