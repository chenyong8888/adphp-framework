<?php
class demo_actions
{
	function __construct(&$pluginManager)
	{
		$pluginManager->register('demo_say_hello', $this, 'say_hello');
	}
	
	function say_hello()
	{
		echo 'Hello World';
	}
}