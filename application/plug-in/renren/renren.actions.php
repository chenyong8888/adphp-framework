<?php
//要使用人人的功能需要使用php_curl（或fopen） 函数
session_start();
require_once 'class/renrenRestApiService.class.php';
require_once 'class/RenrenOAuthApiService.class.php';


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
		$callBackUrl = urlencode(redirecturi);
		return 'https://graph.renren.com/oauth/authorize?client_id='.APPID.'&response_type=code&scope='.scope.'&state=a%3d1%26b%3d2&redirect_uri='.$callBackUrl.'&x_renew=true';
	}
	
	public function checkAuth(){
		$code = $_GET["code"];
		if($code){
			$this->oauthApi = new RenrenOAuthApiService();
			$post_params = array('client_id'=> APIKey,
					'client_secret'=> SecretKey,
					'redirect_uri'=> redirecturi,
					'grant_type'=>'authorization_code',
					'code'=>$code
			);
			$token_url='http://graph.renren.com/oauth/token';
			$access_info=$this->oauthApi->rr_post_curl($token_url,$post_params);//使用code换取token
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
		$res = $this->restApi->rr_post_curl('users.getInfo', $params);//curl函数发送请求
		//$res = $restApi->rr_post_fopen('users.getInfo', $params);//如果你的环境无法支持curl函数，可以用基于fopen函数的该函数发送请求
		return $res;		
	}
	
}




















