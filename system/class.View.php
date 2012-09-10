<?php
/**
 * 
 * @author chen yong
 *
 */
class View{	
	protected $template = NULL;
	protected $vars = array();
	protected $cacheTime = 0;
		
	function __construct(){}
    
	public function assign($data = array())
    {
        if(is_array($data) && !empty($data)){
            $this->vars = $data;
        }else{
            $this->vars = array();
        }
    }
        
    public function cache($time=0){
    	$this->cacheTime = $time;
    	$this->setTemplate();
    	if($this->template->CheckCache()){
    		include($this->template->cacheFile);
			exit;
    	}
    }
        
	public function display($files){	
		$this->setTemplate();
		foreach($files as $file){
			$templateFile = ROOT_PATH . '/application/views/'.$file.$this->template->templateSuffix;		
			if(!file_exists($templateFile)){		
				exit('views file '.$file . ' not found!');
			}
		}		
		$this->template->display($files, $this->vars);
	}
	
	protected function setTemplate(){
    	if(empty($this->template)){
	    	include_once(ROOT_PATH.'/system/class.Template.php');		
			$this->template = Template::GetInstance();		
			$this->template->templatePath = ROOT_PATH.'/'.DEFAULT_APP_PATH.'/'.DEFAULT_TEMPLATE_PATH.'/';
			$this->template->cache = $this->cacheTime == 0 ? FALSE : TRUE;			
			$this->template->cachePath = ROOT_PATH.'/'.DEFAULT_APP_PATH.'/'.DEFAULT_CACHE_PATH.'/';
			$this->template->cacheLifeTime = $this->cacheTime;
			$this->template->templateSuffix = '.php';
			$this->template->cacheFile = $this->template->cachePath . md5($_SERVER['PATH_INFO']) . $this->template->templateSuffix;
    	}
    }

}