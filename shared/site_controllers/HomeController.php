<?php


App::import("Controller","LocalApp");
class HomeController extends LocalAppController {
	
	public $uses = array();
	
	public function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->initPermissions();
		
		$this->Auth->allow("*");
		

	}

	
	public function index() {
		
		
		
	}
	
	
	
}

?>