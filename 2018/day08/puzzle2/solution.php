<?php

$input   = file_get_contents(__DIR__ . "/../input.txt");
$numbers = preg_split('/\s+/', $input);


$root = processSubnodes($numbers);
echo $metadataSum;

function processSubnodes(&$numbers)
{

	$node = new Node();
	$numSubnodes  = array_shift($numbers);
	$numMetanodes = array_shift($numbers);

	if($numSubnodes >0){
		for($i = 0; $i < $numSubnodes; $i++) {
			$node->addChildNode(processSubnodes($numbers));
		}
	}
	for($i = 0; $i < $numMetanodes; $i++) {
		$node->addMetadata(array_shift($numbers));
	}
	return $node;
}

echo $root->getValue();



class Node
{

	/** @var Node[] */
	public $childNodes = [];

	/** @var int[] */
	public $metadata = [];

	public function addChildNode($node)
	{
		$this->childNodes[] = $node;
	}

	public function addMetadata($m)
	{
		$this->metadata[] = $m;
	}

	/**
	 * @return int
	 */
	public function getValue()
	{
		if(count($this->childNodes) == 0) {
			$sum = array_sum($this->metadata);
		} else {
			$sum = 0;
			foreach($this->metadata as $index) {
				if($index == 0 || !array_key_exists($index-1,$this->childNodes)){
					$value = 0;
				} else {
					$value = $this->childNodes[$index - 1]->getValue();
				}
				$sum += $value;
			}

		}

		return $sum;
	}


}

