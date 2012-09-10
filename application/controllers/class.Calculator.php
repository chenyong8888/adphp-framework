<?php
class Calculator extends Controller{
	
	public function __construct(){
		parent::__construct();	
	}
	
	public function index(){			
		$this->addtemplate('calculator/index');		
		$this->display();
	}
	
	public function result(){		
		$serverMaxClick = $_POST['serverMaxClick'];	
		$dayvalue 		= $_POST['dayvalue'];
		$maxvalue 		= $_POST['maxvalue'];
		$pagesize 		= $_POST['pagesize'];
		$waitTime		= 5;
		
		$data = array(			
			'dayvalue' => $dayvalue
			,'maxvalue' => $maxvalue
			,'pagesize' => $pagesize	
			,'waitTime' => $waitTime
						
			,'webServer1' => ceil($dayvalue / (24*60*60*$serverMaxClick))
			,'databaseServer1' => ceil($dayvalue / (24*60*60*$serverMaxClick*2)).'(slave) + n(master)'
			,'net1' => ceil($pagesize * $dayvalue / (24*60*60*1024))
					
			,'webServer2' => ceil($maxvalue / $serverMaxClick)
			,'databaseServer2' => ceil($maxvalue / ($serverMaxClick*2)).'(slave) + n(master)'
			,'net2' => ceil($pagesize * $maxvalue / 1024 / $waitTime)
		);
		$this->addtemplate('calculator/index',$data);		
		$this->display();
	}	
}
















