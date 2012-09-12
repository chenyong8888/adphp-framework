<?php
session_start();
require_once 'Config.php';
require_once 'Tencent.php';

/**
 * 
 * @author chenyong
 * Must open the php_curl function
 *
 */
class qqweibo_actions
{
	protected $o = null;
	
	function __construct(&$pluginManager)
	{
		OAuth::init(qq_client_id, qq_client_secret);
		Tencent::$debug = qq_debug;
		
		$pluginManager->register('qqweibo_callAuthPage', $this, 'callAuthPage');
		$pluginManager->register('qqweibo_checkAuth', $this, 'checkAuth');
		$pluginManager->register('qqweibo_loginUserInfo', $this, 'loginUserInfo');
		
	}
	
	public function callAuthPage(){
		$url = OAuth::getAuthorizeURL(qq_callback_url);
		return $url;
	}
	
	public function checkAuth(){
		if ($_GET['code']) {
	        $code = $_GET['code'];
	        $openid = $_GET['openid'];
	        $openkey = $_GET['openkey'];
	        $url = OAuth::getAccessToken($code, qq_callback_url);
	        $r = Http::request($url);
	        parse_str($r, $out);
	        if ($out['access_token']) {
	            $_SESSION['t_access_token'] = $out['access_token'];
	            $_SESSION['t_refresh_token'] = $out['refresh_token'];
	            $_SESSION['t_expire_in'] = $out['expire_in'];
	            $_SESSION['t_code'] = $code;
	            $_SESSION['t_openid'] = $openid;
	            $_SESSION['t_openkey'] = $openkey;
	            $r = OAuth::checkOAuthValid();
	            if ($r) {
	                return true;
	            } else {
	                return false;
	            }
	        } else {
	            //exit($r);
	        	return false;
	        }
	    } else {
	        if ($_GET['openid'] && $_GET['openkey']){
	            $_SESSION['t_openid'] = $_GET['openid'];
	            $_SESSION['t_openkey'] = $_GET['openkey'];
	            $r = OAuth::checkOAuthValid();
	            if ($r) {
	                header('Location: ' . qq_callback_url);
	            } else {
	                 return false;
	            }
	        } else{
	            $url = OAuth::getAuthorizeURL(qq_callback_url);
	            header('Location: ' . $url);
	        }
	    }
	}
	
	public function loginUserInfo(){
		return Tencent::api('user/info');
	}
	
}




















