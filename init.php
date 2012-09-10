<?php
/**
 * 
 * @author chen yong
 *
 */
header("Content-type:text/html;charset=utf-8");
!defined('ROOT_PATH') && define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));
require_once ROOT_PATH.'/application/config/class.Config.php';
/**
 * 
 * @param $className
 * @return include_once class file
 */
function __autoload($className){
	include_once ROOT_PATH.'/system/class.'.$className.'.php';
}