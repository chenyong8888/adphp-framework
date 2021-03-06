<?php
/**
 * 
 * @author chenyong
 *
 */
class SinaWeibo extends Controller{	
	
	public function __construct(){
		parent::__construct();
		$this->loadPlugin('sinaweibo');
	}
	
	public function index(){
		$data = array(
			'callAuthPage' => $this->pluginManager->trigger('sinaweibo_callAuthPage')
		);
		$this->addtemplate('sinaweibo/index',$data);
		$this->display();
	}
	
	public function callBack(){
		if($this->pluginManager->trigger('sinaweibo_checkAuth')){
			$this->addtemplate('sinaweibo/callback');
			$this->display();
		}else{
			$this->index();
		}
	}
	
	public function userInfo(){
		print_r($this->pluginManager->trigger('sinaweibo_loginUserInfo'));
	}
	
}
















