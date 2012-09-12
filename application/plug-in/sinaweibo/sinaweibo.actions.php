<?php
session_start();
include_once( 'config.php' );
include_once( 'saetv2.ex.class.php' );
/**
 *
 * @author chenyong
 * Must open the php_curl function
 *
 */
class sinaweibo_actions
{
	protected $o = null;
	protected $c = null;
	
	function __construct(&$pluginManager)
	{
		if(sina_WB_AKEY=='' || sina_WB_SKEY==''){
			echo 'sina WB_AKEY and WB_SKEY cannot be null';
			exit;
		}
		$this->o = new SaeTOAuthV2( sina_WB_AKEY , sina_WB_SKEY );
		$pluginManager->register('sinaweibo_callAuthPage', $this, 'callAuthPage');
		$pluginManager->register('sinaweibo_checkAuth', $this, 'checkAuth');
		$pluginManager->register('sinaweibo_loginUserInfo', $this, 'loginUserInfo');
	}
	
	public function callAuthPage(){
		return $this->o->getAuthorizeURL(sina_WB_CALLBACK_URL);
	}
	
	public function checkAuth(){
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = sina_WB_CALLBACK_URL;
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
			$this->c = new SaeTClientV2( sina_WB_AKEY , sina_WB_SKEY , $_SESSION['token']['access_token'] );
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




















