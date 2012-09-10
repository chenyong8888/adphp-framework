<?php
/**
 * 
 * @author chen yong
 *
 */
class Controller{

	protected $view = NULL;	
	protected $load = NULL;
	protected $displayfile = array();
	protected $displaydata = array();	
	protected $pluginManager = NULL;
		
	public function __construct(){
		error_reporting(E_ALL ^ E_NOTICE);
		$this->view = new View();
		$this->load = new Load();
		$this->pluginManager = new pluginManager();
	}
	
	public function addtemplate($file,$data = array()){
		array_push($this->displayfile,$file);
		array_push($this->displaydata,$data);
	}

	public function display(){
		$this->view->assign($this->displaydata);
		$this->view->display($this->displayfile);
	}

	public function run(){	
			
		if(PATHINFO) $control = $this->uriSegment(1); else $control = $_GET['control'];
		if(empty($control)){
			$control = DEFAULT_CONTROL;
		}
		if(PATHINFO) $action = $this->uriSegment(2); else $action = $_GET['action'];
		if(empty($action)){
			$action = DEFAULT_ACTION.DEFAULT_URL_SUFFIX;
		}		
		
		$control = $this->filterSuffix($control);
		$controlFile = ROOT_PATH . '/application/Controllers/class.'.$control.'.php';
		if(!file_exists($controlFile)){
			exit('controller file no found : '.$controlFile);
		}else{
			include_once($controlFile);
		}		
		if(!class_exists($control)){
			exit('class no found : ' . $control);
		}else{
			$instance = new $control();
			$action = $this->filterSuffix($action);
		}			
		if(!method_exists($instance, $action)){			
			exit('method no found : '.$control.'.'.$action);
		}		
		$instance->$action();
	}
	
	private function filterSuffix($str){
		if(stristr($str,DEFAULT_URL_SUFFIX)!=False){
				$str = substr($str,0,strlen($str)-strlen(DEFAULT_URL_SUFFIX));	
		}
		return $str;
	}

	protected function uriSegment($number){
		if(isset($_SERVER['PATH_INFO'])){
			$path = $_SERVER['PATH_INFO'];
			$paths = explode('/', $path);			
			return @$paths[$number];
		}else{
			exit('System does not support the "$ _SERVER [\'PATH_INFO\']"');
		}
	}	
	
	public function redirect($url){
		$redirect = "Location: $url ";
		echo header($redirect);
		exit;
	}
	
	public function site_url(){
		if(INDEX_PAGE!=''){
			return 'http://'.BASE_URL.'/'.INDEX_PAGE.'/';
		}else{
			return $this->base_url();
		}
	}
	
	public function base_url(){
		return 'http://'.BASE_URL.'/';
	}
}






















