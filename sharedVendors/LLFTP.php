<?php

class LLFTP {
	
	private $conn = false;
	private $un = "berrics-ht";
	private $pw = "yteem8";
	public $domain = "berrics.upload.llnw.net";
	
	public function __construct() {
		
		
	}
	
	public function connect() {
		
		if(!$conn) { 
		
			$this->conn = ftp_connect($this->domain);
		
			ftp_login($this->conn,$this->un,$this->pw);
			
			ftp_pasv($this->conn,true);
			
		}
		
	}
	
	public function close() {
		
		
		
	}
	
	
	private function checkFileExists() {
		
		
		
	}
	
	private function renameFileRevision() {
		
		
		
	}
	
	
	
}