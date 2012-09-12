<?php
/**
 * 
 * @author chenyong
 *
 */
class Renren extends Controller{
	
	public function __construct(){
		parent::__construct();
		$this->loadPlugin('renren');
	}

	public function index(){
		$data = array(
				'callAuthPage' => $this->pluginManager->trigger('renren_callAuthPage')
		);
		$this->addtemplate('renren/index',$data);
		$this->display();
	}
	
	public function callBack(){
		if($this->pluginManager->trigger('renren_checkAuth')){
			$this->addtemplate('renren/callback');
			$this->display();
		}else{
			$this->index();
		}
	}
	
	public function userInfo(){
		print_r($this->pluginManager->trigger('renren_loginUserInfo'));
	}
	
}

















