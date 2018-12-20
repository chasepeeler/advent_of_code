<?php
ini_set('memory_limit','8G');
define('C',0);
define('R',1);
define('D',1);
define('L',0);
define('LOC1','AA');
define('MC','()');

$input = file(__DIR__."/../input.txt");
$locations = [];
$maxR = $maxC = 0;
for($i=0,$j=LOC1;$i<count($input);$i++,$j++){
	$locations[$j] = explode(", ",$input[$i]);
	$maxC = max($maxC,$locations[$j][C]);
	$maxR = max($maxR, $locations[$j][R]);
}



$maxR+=400;
$maxC+=400;

$minR = 0;
$minC = 0;

$distances = [];

foreach($locations as $loc => $coord) {
	for($r=$minR;$r<=$maxR;$r++){
		for($c=$minC;$c<=$maxC;$c++){
			$distances[$r][$c][] = [strtolower($loc),abs($r-$coord[R])+abs($c-$coord[C])];
		}
	}
}

$closest = [];

for($r = $minR; $r <= $maxR; $r++) {
	for($c = $minC; $c <= $maxC; $c++) {
		usort($distances[$r][$c],function($a,$b){
			return $a[D] <=> $b[D];
		});
		$d = $distances[$r][$c];
		if($d[0][D] == $d[1][D]){
			$closest[$r][$c] = MC;
		} elseif($d[0][D] == 0) {
			$closest[$r][$c] = strtoupper($d[0][L]);
		} else {
			$closest[$r][$c] = $d[0][L];
		}
	}
}
//echo PHP_EOL;
//for($r = 0; $r <= $maxR; $r++) {
//	for($c = 0; $c <= $maxC; $c++) {
//		echo $closest[$r][$c];
//	}
//	echo PHP_EOL;
//}
//exit;
$dqs    = [];
$counts = [];
for($r = $minR; $r <= $maxR; $r++) {
	for($c = $minC; $c <= $maxC; $c++) {
		if($r == 0 || $c == 0 || $r == $maxR || $c == $maxC) {
			$dqs[] = strtolower($closest[$r][$c]);
		}
		$counts[strtolower($closest[$r][$c])]++;
	}
}

$dqs = array_unique($dqs);

$max = 0;
foreach($counts as $l => $c) {
	if(!in_array($l, $dqs)) {
		$max = max($max, $c);
	}
}

echo $max;


//class grid implements Iterator {
//	protected $points = [];
//
//	protected $minRow;
//	protected $maxRow;
//	protected $minCol;
//	protected $maxCol;
//
//	protected $currentRow = 0;
//	protected $currentCol = 0;
//
//	public function __construct($minRow,$minCol,$maxRow,$maxCol){
//		for($r=$minRow;$r<=$maxRow;$r++){
//			for($c=$minCol;$c<=$maxCol;$c++){
//				$p = new point($r,$c,"-");
//				$this->addPoint($p);
//			}
//		}
//		$this->minCol = $minCol;
//		$this->minRow = $minRow;
//		$this->maxCol = $maxCol;
//		$this->maxRow = $maxRow;
//		$this->rewind();
//	}
//
//	public function addPoint($point){
//		$this->points[$point->row][$point->col] = $point;
//	}
//
//	public function atLastColumn(){
//		return $this->currentCol == $this->maxCol;
//	}
//
//	public function __toString(){
//		$out = "";
//		foreach($this as $point){
//			$out .= $point->value;
//			if($this->atLastColumn()){
//				$out .= PHP_EOL;
//			}
//		}
//		return $out;
//	}
//
//
//	/**
//	 * Return the current element
//	 *
//	 * @link  https://php.net/manual/en/iterator.current.php
//	 * @return mixed Can return any type.
//	 * @since 5.0.0
//	 */
//	public function current()
//	{
//		return $this->points[$this->currentRow][$this->currentCol];
//	}
//
//	/**
//	 * Move forward to next element
//	 *
//	 * @link  https://php.net/manual/en/iterator.next.php
//	 * @return void Any returned value is ignored.
//	 * @since 5.0.0
//	 */
//	public function next()
//	{
//		$this->currentCol++;
//		if($this->currentCol > $this->maxCol){
//			$this->currentCol = $this->minCol;
//			$this->currentRow++;
//		}
//	}
//
//	/**
//	 * Return the key of the current element
//	 *
//	 * @link  https://php.net/manual/en/iterator.key.php
//	 * @return mixed scalar on success, or null on failure.
//	 * @since 5.0.0
//	 */
//	public function key()
//	{
//		return $this->currentRow.'x'.$this->currentCol;
//	}
//
//	/**
//	 * Checks if current position is valid
//	 *
//	 * @link  https://php.net/manual/en/iterator.valid.php
//	 * @return boolean The return value will be casted to boolean and then evaluated.
//	 * Returns true on success or false on failure.
//	 * @since 5.0.0
//	 */
//	public function valid()
//	{
//		return($this->currentRow <= $this->maxRow && $this->currentCol <= $this->maxCol && $this->currentCol >= $this->minCol && $this->currentRow >= $this->minRow);
//	}
//
//	/**
//	 * Rewind the Iterator to the first element
//	 *
//	 * @link  https://php.net/manual/en/iterator.rewind.php
//	 * @return void Any returned value is ignored.
//	 * @since 5.0.0
//	 */
//	public function rewind()
//	{
//		$this->currentRow = $this->minRow;
//		$this->currentCol = $this->minCol;
//	}
//
//}
//
//
//class abstractPoint {
//	public $row;
//	public $col;
//
//	public function __construct($row, $col)
//	{
//		$this->row          = $row;
//		$this->col          = $col;
//	}
//
//	public function distanceToPoint(abstractPoint $point)
//	{
//		return abs($this->row - $point->row) + abs($this->col - $point->col);
//	}
//
//}
//
//class hub extends abstractPoint {
//	public $name;
//
//	public function __construct($row, $col, $name)
//	{
//		parent::__construct($row, $col);
//		$this->name = $name;
//	}
//
//}
//
//class point extends abstractPoint {
//	public $value;
//
//	protected $hubDistances;
//
//	public function __construct($row,$col,$value){
//		$this->hubDistances = new \SplObjectStorage();
//		parent::__construct($row, $col);
//		$this->value = $value;
//	}
//
//	/**
//	 * @param hub $hub
//	 *
//	 * @return void
//	 */
//	public function logHub($hub){
//		$this->hubDistances[$hub] = $hub->distanceToPoint($this);
//	}
//
//	/**
//	 * @return hub[]
//	 */
//	public function closestHubs(){
//		$min_distance = PHP_INT_MAX;
//		$hubs = [];
//		foreach($this->hubDistances as $hub){
//			$distance = $this->hubDistances[$hub];
//
//			if($min_distance > $distance){
//				$min_distance = $distance;
//				$hubs = [];
//			}
//			if($distance == $min_distance){
//				$hubs[] = $hub;
//			}
//		}
//		return $hubs;
//	}
//
//}
//
//class distanceToHub {
//	public $hub;
//	public $distance;
//}
