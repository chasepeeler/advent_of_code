<?php

$input = file(__DIR__."/../input.txt");

usort($input,function($a,$b){
	return getDateTime($a) <=> getDateTime($b);
});
$currentGuard = 0;
$freq = [];
$time = [];
$wakes = [];
$sleeps = [];
$guards = [];
foreach($input as $log){
	$datetime = getDateTime($log);
	$minute = intval($datetime->format("i"));
	if(preg_match('/Guard #(\d+)/',$log,$m)){
		$currentGuard = intval($m[1]);
		$guards[] = $currentGuard;
	} elseif(false !== stripos($log,"asleep")){
		$sleeps[$currentGuard][$datetime->format("Ymd")][] = $minute;
	} elseif(false !== stripos($log,"wakes")){
		$wakes[$currentGuard][$datetime->format("Ymd")][] = $minute;
	}
}

$guards = array_unique($guards);
foreach($guards as $guard){
	$dates = array_unique(array_merge(array_keys($sleeps[$guard]),array_keys($wakes[$guard])));
	foreach($dates as $date){
		$sleeping = false;
		for($i = 0; $i <= 59; $i++) {
			if(in_array($i,$sleeps[$guard][$date])){
				$sleeping = true;
			}
			if(in_array($i, $wakes[$guard][$date])) {
				$sleeping = false;
			}
			if($sleeping){
				$time[$guard]++;
				$freq[$guard][$i]++;
			}
		}
	}
}



asort($time);
$time = array_reverse($time,true);

$time = array_values(array_flip($time));
$guard = $time[0];

$f = $freq[$guard];
asort($f);

$f = array_reverse($f,true);
$f = array_values(array_flip($f));

$minute = $f[0];

echo "{$guard} x {$minute} = ".($guard * $minute).PHP_EOL;






function getDateTime($log){
	preg_match('/\[(.+)\]/', $log, $m);
	return new \DateTime($m[1]);
}

