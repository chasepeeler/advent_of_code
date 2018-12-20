<?php
ini_set("memory_limit","8G");
$input = file(__DIR__."/../input.txt");

$grid = new Grid();
$regex = '/position=<\s*(-?\d+)\s*,\s*(-?\d+)>\s*velocity=<\s*(-?\d+)\s*,\s*(-?\d+)\s*>/';
for($i=0;$i<count($input);$i++){
	preg_match($regex,$input[$i],$m);
	$point = new Point($m[1],$m[2],$m[3],$m[4]);
	$grid->addPoint($point);
}

$i = 0;
while(true){
	try {
		$grid->move();
		$i++;
	} catch (\Exception $e){

		$grid->reset();
		$grid->move($i,true);
		echo $grid;
		echo "Second {$i}" . PHP_EOL;
		exit;
	}
}







class Point {
	public $row;
	public $col;

	public $vRow;
	public $vCol;

	public $currentRow;
	public $currentCol;

	public function __construct($row,$col,$vRow,$vCol)
	{
		$this->row = $row;
		$this->col = $col;
		$this->vRow = $vRow;
		$this->vCol = $vCol;

		$this->currentRow = $row;
		$this->currentCol = $col;

	}

	public function reset(){
		$this->currentRow = $this->row;
		$this->currentCol = $this->col;
	}

	public function move($seconds=1){
			$this->currentRow += ($this->vRow * $seconds);
			$this->currentCol += ($this->vCol * $seconds);

	}

}

class Grid {

	const C = 0;
	const R = 1;

	/** @var Point[] */
	public $points = [];

	protected $coords = [];

	protected $minRow;
	protected $maxRow;
	protected $minCol;
	protected $maxCol;

	public function __construct()
	{
		$this->minRow = PHP_INT_MAX;
		$this->maxRow = -PHP_INT_MAX;
		$this->minCol = PHP_INT_MAX;
		$this->maxCol = -PHP_INT_MAX;
	}

	/**
	 * @param Point $point
	 *
	 * @return void
	 */
	public function addPoint($point){
		$this->coords[$point->currentRow][$point->currentCol] = true;
		$this->points[] = $point;

	}

	public function move($seconds = 1,$force=false){

		$old = ($this->maxRow - $this->minRow) * ($this->maxCol - $this->minCol);
		$this->minRow = PHP_INT_MAX;
		$this->maxRow = -PHP_INT_MAX;
		$this->minCol = PHP_INT_MAX;
		$this->maxCol = -PHP_INT_MAX;

		foreach($this->points as $point){
			$this->coords[$point->currentRow][$point->currentCol] = false;
			$point->move($seconds);
			$this->coords[$point->currentRow][$point->currentCol] = true;

			$this->minRow = min($this->minRow, $point->currentRow);
			$this->maxRow = max($this->maxRow, $point->currentRow);

			$this->minCol = min($this->minCol, $point->currentCol);
			$this->maxCol = max($this->maxCol, $point->currentCol);
		}
		$new = ($this->maxRow - $this->minRow) * ($this->maxCol - $this->minCol);
		if(!$force && $old < $new) {
			throw new \Exception("Done");
		}


	}

	public function reset(){
		$p = $this->points;
		$this->points = [];
		$this->coords = [];
		foreach($p as $point){
			$point->reset();
			$this->addPoint($point);
		}
		unset($p);
	}


	public function __toString()
	{
		$buffer = "";
		for($r=$this->minCol-3;$r<=$this->maxCol+3;$r++){
			for($c=$this->minRow-3;$c<=$this->maxRow+3;$c++){
				$buffer .= $this->coords[$c][$r] ? "#" : ".";
			}
			$buffer .= PHP_EOL;
		}

		return $buffer;
	}


}

