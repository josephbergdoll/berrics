<?php

class SplashRoute extends CakeRoute {
	
	public function parse($url) {
		
		$params = parent::parse($url);

		if(empty($params)) {
			
			return false;
			
		}
		
		$news = false;

		if(time()>=(strtotime("2011-08-14")) && in_array(strtoupper(date("D")),array("SUN"))) {
			
			$news = true;
			
		}
		
		//$news = true;
		
		$params['controller'] = "splash";
		$params['action'] = "weekendtage";
		
		if($news) {
			
			$params['controller'] = "newsv2";
			$params['action'] = "section";
			$params['pass'][] = date("Y");
			$params['pass'][] = date("m");
			$params['pass'][] = date("d");
			
		}
		
		if(in_array(date('Y-m-d'),array('2012-05-26'))) {
			
			$params['controller'] = "splash";
			$params['action'] = "index";
			
		} 

		
		return $params;
		
	}
	
}
