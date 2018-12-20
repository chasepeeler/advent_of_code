<?php

$input = file(__DIR__."/../input.txt");
/** @var Step[] $steps */
$steps = [];


foreach(range('A','Z') as $letter){
	$steps[$letter] = new Step($letter);
}

for($i=0;$i<count($input);$i++){
	list($prereq,$for) = parseInstruction($input[$i]);
	$prereqStep = $steps[$prereq];
	$forStep = $steps[$for];
	$forStep->addPrereq($prereqStep);
}

$queue = new StepsQueue();

foreach($steps as $step){
	if(!$step->hasPrereqs()){
		$queue->insert($step);
	}
}

$order = "";

while(!$queue->isEmpty()){
	/** @var Step $step */
	$step = $queue->extract();
	$step->complete = true;
	if(false === stristr($order, $step->name)) {
		$order .= $step->name;
		foreach($step->nextSteps as $nextStep){
			if($nextStep->canExecute()) {
				$queue->insert($nextStep);
			}
		}
	}
}

echo $order;



function parseInstruction($instruction){
	$regex = '/Step (\w) must be finished before step (\w) can begin\./';
	preg_match($regex,$instruction,$m);
	return [$m[1],$m[2]];

}



class Step {
	public $name;
	public $complete = false;

	/**
	 * @var Step[]
	 */
	public $prereqs = [];

	/**
	 * @var Step[]
	 */
	public $nextSteps = [];

	public function hasPrereqs(){
		return count($this->prereqs) > 0;
	}

	public function __construct($name){
		$this->name = $name;
	}


	public function addPrereq($step){
		$this->prereqs[] = $step;
		$step->nextSteps[] = $this;
	}

	public function canExecute(){
		foreach($this->prereqs as $prereq){
			if(!$prereq->complete){
				return false;
			}
		}
		return true;
	}

}

class StepsQueue extends \SplPriorityQueue {

	/**
	 * @param Step $a
	 * @param Step $b
	 *
	 * @return int
	 */
	public function compare($a,$b){
		return -1*parent::compare($a,$b);
	}

	/**
	 * @param Step $data
	 * @param mixed  $priority
	 *
	 * @return void
	 */
	public function insert($data,$priority=null){
		parent::insert($data,$priority ?? $data->name);
	}


}