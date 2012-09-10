<?php
/**
 * 
 * @author chen yong
 *
 */
class Load{	
			
	function __construct(){}
    
	public function model($modelName){       
		return $this->loadFile($modelName,'models');
    }
    
	public function language($languageName){		 
		return $this->loadFile($languageName,'language');
    }

	public function tools($toolsName){
        return $this->loadFile($modelName,'tools');
    }
    
    private function loadFile($name,$class){
    	switch ($class){
    		case 'language':
    			$file = ROOT_PATH . '/'.DEFAULT_APP_PATH.'/language/'.DEFAULT_LANGUAGE.'/'.$name.'.php';
    			!file_exists($file) && exit('language file '.$name.' no found');
    			include_once($file);
    			break;
    		default:
    			$file = ROOT_PATH . '/'.DEFAULT_APP_PATH.'/'.$class.'/class.'.$name.'.php';
    			!file_exists($file) && exit($class.' file '.$name.' no found');
    			include_once($file);
    			!class_exists($name) && exit($class.' class '.$name.' no found');
    			$obj = new $name();
    			return $obj;
    	}
 
    }

}