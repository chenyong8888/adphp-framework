<?php
include "phpqrcode.php";

/**
 * 
 * @author chenyong
 *
 */
class phpqrcode_actions
{
	public function __construct(&$pluginManager)
	{
		$pluginManager->register('qrcode_createCode', $this, 'createCode');
	}
	
	public function createCode($config)
	{	
		QRcode::png($config[0], $config[1], $config[2], $config[3], $config[4], $config[5]);
	}
}