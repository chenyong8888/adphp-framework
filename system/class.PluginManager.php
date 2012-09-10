<?php
/**
 *
 * @author chenyong
 *
 */
class PluginManager{

	private $_listeners = array();

	public function __construct(){}
	
	/**
	 * 
	 * @param $plugins
	 */
	public function loadPlugin($plugins){
		if($plugins)
		{
			foreach($plugins as $plugin)
			{
				$tempPath = ROOT_PATH .'/'.DEFAULT_APP_PATH .'/plug-in/'.$plugin['name'].'/'. $plugin['name'] .'.actions.php';
				if (@file_exists($tempPath))
				{
					include_once($tempPath);
					$class = $plugin['name'].'_actions';
					if (class_exists($class))
					{
						new $class($this);
					}
				}
			}
		}
	}
	
	/**
	 * 
	 * @param  $hook
	 * @param  $reference
	 * @param  $method
	 */
	public function register($hook, &$reference, $method)
	{
		$key = get_class($reference).'->'.$method;
		$this->_listeners[$hook][$key] = array(&$reference, $method);
	}
	
	/**
	 * 
	 * @param key $hook
	 * @param method param $data
	 * @return string
	 */
	public function trigger($hook, $data='')
	{
		$result = NULL;
		if (isset($this->_listeners[$hook]) && is_array($this->_listeners[$hook]) && count($this->_listeners[$hook]) > 0)
		{
			$i = 0;
			foreach ($this->_listeners[$hook] as $listener)
			{
				$class =& $listener[0];
				$method = $listener[1];
				if(method_exists($class,$method))
				{
					$result = $class->$method($data);
				}
			}
		}
		return $result;
	}
}









