<?php

App::import("Controller","AdminApp");

class UserContestsController extends AdminAppController {
	
	
	public function beforeFilter() {
		
		parent::beforeFilter();
		
		$this->initPermissions();
		
	}
	
	
	public function index() {
		
		$data = $this->paginate("UserContest");
		
		$this->set(compact("data"));
		
	}
	
	public function add() {
		
		if(count($this->data)>0) {
			
			if($this->UserContest->save($this->data)) {
				
				$this->Session->setFlash("Contest has been created");
				return $this->redirect("/user_contests");
				
			} else {
				
				$this->Session->setFlash("There was an error while saving the event. Please try again.");
				
			}
			
		}
		
	}
	
	public function edit($id = false) {
		
		if(!$id) {
			
			$this->Session->setFlash("Invalid URL");
			
			return $this->redirect("/user_contests");
			
		}
		
		//handle the save
		if(count($this->data)>0) {
			
			
		} else {
			
			$this->data = $this->UserContest->find("first",array(
			
				"conditions"=>array(
					"UserContest.id"=>$id
				),
				"contain"=>array()
			
			));
			
			
		}
		
		
		
		
	}
	
	public function mark_entry() {
		
		switch($this->params['named']['type']) {
			
			case "winner":
				$update = array(
					"winner"=>1,
				);
			break;
			case "loser":
				$update = array(
					"winner"=>0
				);
			break;
			
		}
		
		$this->loadModel("UserContestEntry");
		
		$this->UserContestEntry->create();
		$this->UserContestEntry->id = $this->params['named']['user_contest_entry_id'];
		$this->UserContestEntry->save($update);
		
		return $this->flash("Update Entry!","/user_contests/view_entries/".$this->params['named']['user_contest_id']);
		
	}
	
	public function view_entries($id = false) {
		
		//let's do some binding
		$this->loadModel("UserContestEntry");
		
		$this->UserContestEntry->bindModel(array("belongsTo"=>array(
		
			"Dailyop"=>array(
				"foreignKey"=>"foreign_key",
			),
			"User"
		
		)));
		
		$this->UserContestEntry->recursive = 1;
		
		$this->paginate['UserContestEntry'] = array(
			"fields"=>array(
				"UserContestEntry.*",
				"Dailyop.*",
				"User.*"
			),
			"conditions"=>array(
				"UserContestEntry.user_contest_id"=>$id
			),
			"joins"=>array(
				"LEFT JOIN dailyops AS `Dailyop` ON (UserContestEntry.foreign_key = Dailyop.id)",
				"INNER JOIN users AS `User` ON (UserContestEntry.user_id = User.id)"
			),
			"contain"=>array(
				
			),
			"limit"=>100
		
		);
		
		if(count($this->data)>0) {
			
			$this->paginate['UserContestEntry']['conditions']['UserContestEntry.foreign_key'] = $this->data['UserContestEntry']['foreign_key'];
			
		}
		
		//make a menu of contest posts to filter thru
		$this->loadModel("Dailyop");
		$postFilter = $this->Dailyop->find("list",array(
			
			"conditions"=>array(
				"Dailyop.contest_post"=>1
			),
			"contain"=>array()
		
		));
		
		$this->set(compact("postFilter"));
		
		$data = $this->paginate("UserContestEntry");
		
		$this->set(compact("data"));
		
	}
	
	public function view_entry($id = false) {
		
		
		
	}
	
	
	
}