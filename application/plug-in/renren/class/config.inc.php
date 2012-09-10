<?php
/*
 * 总体配置文件，包括API Key, Secret Key，以及所有允许调用的API列表
 * This file for configure all necessary things for invoke, including API Key, Secret Key, and all APIs list
 *
 * @Modified by mike on 17:54 2011/12/21.
 * @Modified by Edison tsai on 16:34 2011/01/13 for remove call_id & session_key in all parameters.
 * @Created: 17:21:04 2010/11/23
 * @Author:	Edison tsai<dnsing@gmail.com>
 * @Blog:	http://www.timescode.com
 * @Link:	http://www.dianboom.com
 */

// $config				= new stdClass;

// $config->APIURL		= 'http://api.renren.com/restserver.do'; //RenRen网的API调用地址，不需要修改
// $config->APPID		= '99273';	//你的API Key，请自行申请
// $config->APIKey		= 'dd3ffbf2bd894ca9819f5dbc82c2f39c';	//你的API Key，请自行申请
// $config->SecretKey	= 'f6d6d1308e314737ac955ba24a5aecc0';	//你的API 密钥
// $config->APIVersion	= '1.0';	//当前API的版本号，不需要修改
// $config->decodeFormat	= 'json';	//默认的返回格式，根据实际情况修改，支持：json,xml
// $config->redirecturi= 'http://127.0.0.1/phpsdk/accesstoken.php';//你的获取code的回调地址，也是accesstoken的回调地址
// $config->scope='publish_feed,photo_upload';

define( "APIURL" , 'http://api.renren.com/restserver.do' );
define( "APPID" , '142905' );
define( "APIKey" , 'fc1d05d2561d42f0afee7ab4ebd02ed8' );
define( "SecretKey" , 'c5b9bb0cfb0d475eaa79e4f629b3a68b' );
define( "APIVersion" , '1.0' );
define( "decodeFormat" , 'json' );

define( "redirecturi" , 'http://10086.client.sina.com.cn/index.php?control=Renren&action=callBack' );
define( "scope" , 'publish_feed,photo_upload' );
?>