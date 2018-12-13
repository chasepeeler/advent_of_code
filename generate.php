<?php

$year = date("Y");


mkdir($year);

for($i=1;$i<=25;$i++){
	$dir = sprintf("%d/day%02d", $year, $i);
	if(!file_exists($dir)) {
		mkdir($dir);
		echo "created {$dir}" . PHP_EOL;
	}
	file_put_contents($dir."/input.txt","");
	for($j=1;$j<=2;$j++){
		$dir2 = $dir . '/puzzle' . $j;
		if(!file_exists($dir2)) {
			mkdir($dir2);
			echo "....puzzle{$j}" . PHP_EOL;
		}
		$file = $dir2.'/solution.php';
		if(!file_exists($file)){
			file_put_contents($file, "<?php".PHP_EOL.PHP_EOL.'$input = file(__DIR__."/../input.txt");'.PHP_EOL);
		}
	}
}
echo "done".PHP_EOL;