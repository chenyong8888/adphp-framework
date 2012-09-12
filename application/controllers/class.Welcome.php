<?php
/**
 * 
 * @author chenyong
 *
 */
class Welcome extends Controller{
	
	public function __construct(){
		parent::__construct();	
	}
	
	public function index(){
		$this->view->cache(1);		
		$data = array(		
			'title' => 'body'
			,'time' => time()
			,'body' => array(1,2,3,4)
		);		
		$this->addtemplate('inc/head',$data);
		$this->addtemplate('index',$data);		
		$this->display();
	}
	
	public function test(){
		$this->view->cache(1);
		$this->load->model('Main');		
		$this->load->language('calendar_lang');
		$Test = $this->load->model('Test');
		
		$pageNo = $this->uriSegment(3);
		if(empty($pageNo)) $pageNo = 3;
		$page = $this->uriSegment(4);
		
		$total = $Test->getRowTotal();
		$list = $Test->get($pageNo*$page,$pageNo);
		
		$body = array(
			'total' => $total
			,'list' => $list
		);		
		$data = array(			
			'body' => $body
		);		
		$this->addtemplate('index',$data);		
		$this->display();
	}
	
	public function createCode(){
		$this->loadPlugin('phpqrcode');
		$config = array('哈哈',false,'M','4','2',false);
		$this->pluginManager->trigger('qrcode_createCode',$config);
	}
}
















