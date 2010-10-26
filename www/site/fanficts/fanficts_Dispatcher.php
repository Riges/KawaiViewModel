<?php

require_once('Vbf/Mvc/Dispatcher.php');

class Fanficts_Dispatcher extends Vbf_Mvc_Dispatcher
{
	public function getDispatchRules()
	{
		return array(
			array('type' => 'action', 'params' => 0, 'url' => '', 'name' => 'summary', 'method' => 'GET'),
			array('type' => 'action', 'params' => 1, 'url' => '', 'name' => 'foruser', 'method' => 'GET'),
			array('type' => 'action', 'params' => 2, 'url' => '', 'name' => '', 'method' => 'GET'),
					
			array('type' => 'action', 'params' => 2, 'url' => 'edit', 'name' => 'edit', 'method' => 'GET'),
			array('type' => 'action', 'params' => 0, 'url' => 'new', 'name' => 'new', 'method' => 'GET'),
			
			array('type' => 'action', 'params' => 2, 'url' => '', 'name' => '', 'method' => 'PUT'),
			array('type' => 'action', 'params' => 0, 'url' => '', 'name' => '', 'method' => 'POST'),
			array('type' => 'action', 'params' => 2, 'url' => '', 'name' => '', 'method' => 'DELETE'),			
			);
	}
}

?>