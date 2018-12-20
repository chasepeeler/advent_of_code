<?php

define('BASE_TIME',60);
define('WORKERS',5);

$input = file(__DIR__ . "/../input.txt");
/** @var Step[] $steps */
$steps = [];


foreach(range('A', 'Z') as $letter) {
	$steps[$letter] = new Step($letter);
}

for($i = 0; $i < count($input); $i++) {
	list($prereq, $for) = parseInstruction($input[$i]);
	$prereqStep = $steps[$prereq];
	$forStep    = $steps[$for];
	$forStep->addPrereq($prereqStep);
}

$queue = new StepsQueue();



foreach($steps as $step) {
	if(!$step->hasPrereqs()) {
		$queue->insert($step);
	}
}

$workers = new Workers();
$completed = [];
$time = 0;
do {
	while(!$queue->isEmpty() && $workers->hasIdleWorkers()){
		$step = $queue->extract();
		$workers->addStep($step);
	}
echo $workers.PHP_EOL;
	$workers->doWork();

	foreach($workers->completedSteps as $step){
		foreach($step->nextSteps as $nextStep){
			if($nextStep->canExecute()){
				$queue->insert($nextStep);
			}
		}
		$completed[] = $step;
	}
	$workers->completedSteps = [];
	$time++;
} while(count($completed) < count($steps));

echo $time;


function parseInstruction($instruction)
{
	$regex = '/Step (\w) must be finished before step (\w) can begin\./';
	preg_match($regex, $instruction, $m);

	return [$m[1], $m[2]];

}


class Step
{

	public $name;

	public $complete = false;

	public $timeRemaining;

	/**
	 * @var Step[]
	 */
	public $prereqs = [];

	/**
	 * @var Step[]
	 */
	public $nextSteps = [];

	public function hasPrereqs()
	{
		return count($this->prereqs) > 0;
	}

	public function __construct($name)
	{
		$this->name = $name;
		$this->timeRemaining = BASE_TIME + (ord($name)-64);
	}


	public function addPrereq($step)
	{
		$this->prereqs[]   = $step;
		$step->nextSteps[] = $this;
	}

	public function canExecute()
	{
		foreach($this->prereqs as $prereq) {
			if(!$prereq->complete) {
				return false;
			}
		}

		return true;
	}

	public function doWork(){
		$this->timeRemaining--;
	}

	public function isWorkDone(){
		return $this->timeRemaining <= 0;
	}

}

class StepsQueue extends \SplPriorityQueue
{

	/**
	 * @param Step $a
	 * @param Step $b
	 *
	 * @return int
	 */
	public function compare($a, $b)
	{
		return -1 * parent::compare($a, $b);
	}

	/**
	 * @param Step  $data
	 * @param mixed $priority
	 *
	 * @return void
	 */
	public function insert($data, $priority = null)
	{
		parent::insert($data, $priority ?? $data->name);
	}


}

class Workers {

	/**
	 * @var Step[]
	 */
	public $steps = [];

	public $completedSteps = [];

	/**
	 * @param Step $step
	 *
	 * @return void
	 */
	public function addStep($step){
		$this->steps[$step->name] = $step;
	}

	/**
	 * @return bool
	 */
	public function hasIdleWorkers(){
		return count($this->steps) < WORKERS;
	}


	public function doWork(){
		foreach($this->steps as $name=>$step){
			$step->doWork();
			if($step->isWorkDone()){
				$step->complete = true;
				$this->completedSteps[] = $step;
				unset($this->steps[$name]);
			}
		}
	}

	public function __toString()
	{
		$buffer = "";
		$i = 1;
		foreach($this->steps as $step){
			$buffer .= "W{$i}: {$step->name} ({$step->timeRemaining}) | ";
		}
		return $buffer;
	}

}