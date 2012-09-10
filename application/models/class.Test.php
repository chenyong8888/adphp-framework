<?php
class Test extends Main{
	
	private $database_table = 'bird_email';
	private $database_mianID = 'email_id';
	
	public function __construct(){
		$this->set_database_table($this->database_table);
		$this->set_database_mianID($this->database_mianID);
	}
}