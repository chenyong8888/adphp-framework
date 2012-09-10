<?php
/**
 * 
 * @author chen yong
 *
 */
class Template
{
	private static $instance 	= NULL;
	public $templatePath		= '';
	public $cachePath			= '';
	public $cache				= FALSE;
	public $cacheLifeTime		= 0;
	public $templateSuffix 	= '.php';
	public $tVars			= array();
	private $templateFile		= array();
	public $cacheFile			= '';
	
	private function __construct(){}
	
	public function display($files, $data = array())
	{
		$this->fetch($files, $data, $this->cache);
	}

	public function fetch($files, $data = array(), $display = FALSE)
	{			
		$this->InitFilePath($files);
		$this->tVars = $data;
		if($display){
			if(!$this->CheckCache()){
				$this->Cache();				
			}else{				
				include($this->cacheFile);				
			}
		}else{			
			$i = 0;
			foreach($this->templateFile as $templateFile){		
				@extract($this->tVars[$i]);
				include($templateFile);				
				$i++;
			}			
		}
	}

	public function Cache()
	{
		$content = '';
		ob_start();
		$i = 0;
		foreach($this->templateFile as $templateFile){		
			@extract($this->tVars[$i]);
			include($templateFile);
			$content = ob_get_contents();
			$i++;
		}
		$info = '<?php /* System cache file,Create in '. date('Y-m-d H:i:s') .' */?>';
		file_put_contents($this->cacheFile, $content.$info);
		return ob_flush();		
	}

	public function CheckCache()
	{
		if(!file_exists($this->cacheFile)){
			return FALSE;
		}		
		if(filemtime($this->cacheFile) + $this->cacheLifeTime < time() && $this->cache){
			return FALSE;
		}		
		return TRUE;		
	}
	
	private function InitFilePath($files)
	{
		foreach($files as $file){
			$templateFile = $this->templatePath . $file . $this->templateSuffix;			
			array_push($this->templateFile,$templateFile);
		}		
		$this->cacheFile = $this->cachePath . md5($_SERVER['PATH_INFO']) . $this->templateSuffix;
	}

	public static function GetInstance()
	{
		if(is_null(self::$instance)){
			self::$instance = new Template();
		}
		return self::$instance;
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
	
	public function static_url(){
		return 'http://'.BASE_URL.'/'.DEFAULT_STATICFILE_PATH.'/';
	}
}



















