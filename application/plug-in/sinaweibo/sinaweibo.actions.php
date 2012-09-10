<?php
//要使用sina微博的功能需要使用php_curl 函数
session_start();
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );

class sinaweibo_actions
{
	protected $o = null;
	protected $c = null;
	
	function __construct(&$pluginManager)
	{
		if(WB_AKEY=='' || WB_SKEY==''){
			echo 'sina WB_AKEY and WB_SKEY cannot be null';
			exit;
		}
		$this->o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
		$pluginManager->register('sinaweibo_callAuthPage', $this, 'callAuthPage');
		$pluginManager->register('sinaweibo_checkAuth', $this, 'checkAuth');
		$pluginManager->register('sinaweibo_loginUserInfo', $this, 'loginUserInfo');
	}
	
	public function callAuthPage(){
		return $this->o->getAuthorizeURL( WB_CALLBACK_URL );
	}
	
	public function checkAuth(){
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $this->o->getAccessToken('code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}
		if ($token) {
			$_SESSION['token'] = $token;
			setcookie( 'weibojs_'.$this->o->client_id, http_build_query($token) );
			return true;
		} else {
			return false;
		}
	}
	
	public function createClient(){
		if(empty($this->c)){
			$this->c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		}
	}
	
	public function loginUserInfo(){
		$this->createClient();
		$ms  = $this->c->home_timeline();
		$uid_get = $this->c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $this->c->show_user_by_id( $uid);
		return $user_message;
	}
	
}




















