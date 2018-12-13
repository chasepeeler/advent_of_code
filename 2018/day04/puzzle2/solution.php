<?php

$input = file(__DIR__ . "/../input.txt");

usort(
	$input,
	function($a, $b) {
		return getDateTime($a) <=> getDateTime($b);
	}
);
$currentGuard = 0;
$freq         = [];
$time         = [];
$wakes        = [];
$sleeps       = [];
$guards       = [];
foreach($input as $log) {
	$datetime = getDateTime($log);
	$minute   = intval($datetime->format("i"));
	if(preg_match('/Guard #(\d+)/', $log, $m)) {
		$currentGuard = intval($m[1]);
		$guards[]     = $currentGuard;
	} elseif(false !== stripos($log, "asleep")) {
		$sleeps[$currentGuard][$datetime->format("Ymd")][] = $minute;
	} elseif(false !== stripos($log, "wakes")) {
		$wakes[$currentGuard][$datetime->format("Ymd")][] = $minute;
	}
}

$guards = array_unique($guards);
foreach($guards as $guard) {
	$dates = array_unique(array_merge(array_keys($sleeps[$guard]), array_keys($wakes[$guard])));
	foreach($dates as $date) {
		$sleeping = false;
		for($i = 0; $i <= 59; $i++) {
			if(in_array($i, $sleeps[$guard][$date])) {
				$sleeping = true;
			}
			if(in_array($i, $wakes[$guard][$date])) {
				$sleeping = false;
			}
			if($sleeping) {
				$time[$guard]++;
				$freq[$guard][$i]++;
			}
		}
	}
}

$gm = [];

for($i=0;$i<=59;$i++){
	$gm[$i] = [0,0,$i];
	foreach($guards as $guard){
		$m = $freq[$guard][$i];
		if($m > $gm[$i][0]){
			$gm[$i] = [$m,$guard,$i];
		}
	}
}



usort($gm,function($a,$b){
	return $b[0] <=> $a[0];
});


$guard = $gm[0][1];
$minute = $gm[0][2];


echo "{$guard} x {$minute} = " . ($guard * $minute) . PHP_EOL;


function getDateTime($log)
{
	preg_match('/\[(.+)\]/', $log, $m);

	return new \DateTime($m[1]);
}

