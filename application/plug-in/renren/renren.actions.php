<?php
session_start();
require_once 'class/renrenRestApiService.class.php';
require_once 'class/RenrenOAuthApiService.class.php';

/**
 *
 * @author chenyong
 * Must open the php_curl function or fopen function
 *
 */
class renren_actions
{
	protected $oauthApi = null;
	protected $restApi = null;
	
	function __construct(&$pluginManager)
	{
		$pluginManager->register('renren_callAuthPage', $this, 'callAuthPage');
		$pluginManager->register('renren_checkAuth', $this, 'checkAuth');
		$pluginManager->register('renren_loginUserInfo', $this, 'loginUserInfo');
	}
	
	public function callAuthPage(){
		$callBackUrl = urlencode(renren_redirecturi);
		return 'https://graph.renren.com/oauth/authorize?client_id='.renren_APPID.'&response_type=code&scope='.renren_scope.'&state=a%3d1%26b%3d2&redirect_uri='.$callBackUrl.'&x_renew=true';
	}
	
	public function checkAuth(){
		$code = $_GET["code"];
		if($code){
			$this->oauthApi = new RenrenOAuthApiService();
			$post_params = array('client_id'=> renren_APIKey,
					'client_secret'=> renren_SecretKey,
					'redirect_uri'=> renren_redirecturi,
					'grant_type'=>'authorization_code',
					'code'=>$code
			);
			$token_url='http://graph.renren.com/oauth/token';
			$access_info=$this->oauthApi->rr_post_curl($token_url,$post_params);
			//$access_info=$this->oauthApi->rr_post_fopen($token_url,$post_params);//如果你的环境无法支持curl函数，可以用基于fopen函数的该函数发送请求
			$access_token=$access_info["access_token"];
			$expires_in=$access_info["expires_in"];
			$refresh_token=$access_info["refresh_token"];
			$_SESSION["access_token"]=$access_token;
			return true;
		}else{
			return false;
		}
	}
	
	public function createClient(){
		$this->restApi = new RenrenRestApiService;
	}
	
	function loginUserInfo(){
		
		$this->createClient();
		$params = array('fields'=>'uid,name,sex,birthday,mainurl,hometown_location,university_history,tinyurl,headurl','access_token'=>$_SESSION["access_token"]);
		$res = $this->restApi->rr_post_curl('users.getInfo', $params);
		//$res = $restApi->rr_post_fopen('users.getInfo', $params);//如果你的环境无法支持curl函数，可以用基于fopen函数的该函数发送请求
		return $res;		
	}
	
}




















