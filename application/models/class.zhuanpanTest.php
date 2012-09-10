<?php
class zhuanpanTest extends Main{
	
	private $database_table = 'test';
	private $database_mianID = 'id';
	
	public function __construct(){
		$this->set_database_table($this->database_table);
		$this->set_database_mianID($this->database_mianID);
	}
}