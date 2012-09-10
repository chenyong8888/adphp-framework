<?php
class QQWeibo extends Controller{
	
	public function __construct(){
		parent::__construct();
		$plugin = array(
				'name' => 'qqweibo'
		);
		$plugins = array($plugin);
		$this->pluginManager->loadPlugin($plugins);
	}
	
	public function index(){
		$data = array(
			'callAuthPage' => $this->pluginManager->trigger('qqweibo_callAuthPage')
		);
		$this->addtemplate('qqweibo/index',$data);
		$this->display();
	}
	
	public function callBack(){
		if($this->pluginManager->trigger('qqweibo_checkAuth')){
			$this->addtemplate('qqweibo/callback');
			$this->display();
		}else{
			$this->index();
		}
	}
	
	public function userInfo(){
		print_r($this->pluginManager->trigger('qqweibo_loginUserInfo'));
	}
	
}
















