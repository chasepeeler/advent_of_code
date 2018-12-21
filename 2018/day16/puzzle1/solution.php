<?php

$input = file(__DIR__."/../samples.txt");

$samples = [];
$insts = Machine::$opcodes;

for($i=0;$i<count($input)-2;$i+=4){
	$s = [];
	$s['before'] = parseRegisterSample($input[$i]);
	$s['instruction'] = trim($input[$i+1]);
	$s['after'] = parseRegisterSample($input[$i+2]);
	$samples[] = $s;
}



$possibles = [];
$count = 0;
$machine = new Machine();
foreach($samples as $sample){
	$instruction = explode(" ",$sample['instruction']);
	$opcode = $instruction[0];
	$c = 0;
	foreach($insts as $inst) {
		$instruction[0] = $inst;
		$machine->setRegisters(...$sample['before']);
		$machine->execute($instruction);
		$result = $machine->getRegisters();
		if($result == $sample['after']){
			$c++;
		}
	}
	if($c >= 3){
		$count++;
	}
}



echo "{$count} samples behave like 3 or more opcodes".PHP_EOL;














function parseRegisterSample($sample){
	$sample = preg_replace('/.*\[(.+)\].*/', '$1', $sample);
	return array_map("intval", explode(", ", $sample));
}



class Machine {

	protected $r = [0, 0, 0, 0];
	protected $ci = 0;

	public static $opcodes = ["addr", "addi", "mulr", "muli", "banr", "bani", "borr", "bori", "setr","seti","gtir","gtri","gtrr","eqir","eqri","eqrr"];


	public function run($instructions){
		while($this->ci < count($instructions)){
			$this->execute($instructions[$this->ci]);
		}
		return $this->getRegisters();
	}

	public function setRegisters($a,$b,$c,$d){
		$this->r = [$a,$b,$c,$d];

	}

	public function execute($instruction){
		if(is_string($instruction)) {
			$instruction = explode(" ", $instruction);
		}
		list($inst, $a, $b, $c) = $instruction;
		if(is_numeric($inst)){
			$inst = self::$opcodes[$inst];
		}

		$this->$inst($a,$b,$c);

	}


	public function getRegisters(){
		return $this->r;
	}

	public function addr($a,$b,$c){
		$this->r[$c] = $this->r[$a] + $this->r[$b];
		$this->ci++;
	}

	public function addi($a,$b,$c){
		$this->r[$c] = $this->r[$a] + $b;
		$this->ci++;
	}

	public function mulr($a,$b,$c){
		$this->r[$c] = $this->r[$a] * $this->r[$b];
		$this->ci++;
	}

	public function muli($a, $b, $c)
	{
		$this->r[$c] = $this->r[$a] * $b;
		$this->ci++;
	}

	public function banr($a, $b, $c)
	{
		$this->r[$c] = $this->r[$a] & $this->r[$b];
		$this->ci++;
	}

	public function bani($a, $b, $c)
	{
		$this->r[$c] = $this->r[$a] & $b;
		$this->ci++;
	}

	public function borr($a, $b, $c)
	{
		$this->r[$c] = $this->r[$a]|$this->r[$b];
		$this->ci++;
	}

	public function bori($a, $b, $c)
	{
		$this->r[$c] = $this->r[$a]|$b;
		$this->ci++;
	}

	public function setr($a,$b,$c){
		$this->r[$c] = $this->r[$a];
		$this->ci++;
	}

	public function seti($a,$b,$c){
		$this->r[$c] = $a;
		$this->ci++;
	}

	public function gtir($a,$b,$c){
		$this->r[$c] = $a > $this->r[$b] ? 1 : 0;
		$this->ci++;
	}

	public function gtri($a,$b,$c){
		$this->r[$c] = $this->r[$a] > $b ? 1 : 0;
		$this->ci++;
	}

	public function gtrr($a, $b, $c)
	{
		$this->r[$c] = $this->r[$a] > $this->r[$b] ? 1 : 0;
		$this->ci++;
	}

	public function eqir($a, $b, $c)
	{
		$this->r[$c] = $a == $this->r[$b] ? 1 : 0;
		$this->ci++;
	}

	public function eqri($a, $b, $c)
	{
		$this->r[$c] = $this->r[$a] == $b ? 1 : 0;
		$this->ci++;
	}

	public function eqrr($a, $b, $c)
	{
		$this->r[$c] = $this->r[$a] == $this->r[$b] ? 1 : 0;
		$this->ci++;
	}



}



