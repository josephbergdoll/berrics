#!/usr/bin/php
<?php

	$dir = dirname(__FILE__);
	
	$total = 190;

	$count = 1;

	$total_rows = 1000000;
	
	$start = 1;
	
	while($count<=$total) {
		
		echo system("$dir/googetl.php --total=$total_rows --start=$start");
		
		$count++;
		$start += $total_rows;
		
	}

?>