<?php

require_once('Vbf/Mvc/Dispatcher.php');

class News_Dispatcher extends Vbf_Mvc_Dispatcher
{
	public function getDispatchRules()
	{
		return array(
			// List
			array('type' => 'action', 'params' => 0, 'url' => '', 'name' => 'list', 'method' => 'GET'),
			
			// Display
			array('type' => 'action', 'params' => 4, 'url' => '', 'name' => 'onenews', 'method' => 'GET'),

			// Create
			array('type' => 'action', 'params' => 0, 'url' => 'new', 'name' => 'new', 'method' => 'GET'),
			array('type' => 'action', 'params' => 0, 'url' => '', 'name' => '', 'method' => 'POST'),
			
			// Edit
			array('type' => 'action', 'params' => 4, 'url' => 'edit', 'name' => 'edit', 'method' => 'GET'),
			array('type' => 'action', 'params' => 1, 'url' => '', 'name' => '', 'method' => 'PUT'),
			
			// Delete
			array('type' => 'action', 'params' => 1, 'url' => 'delete', 'name' => 'delete', 'method' => 'GET'),		
			array('type' => 'action', 'params' => 1, 'url' => '', 'name' => '', 'method' => 'DELETE'),
			);
	}
}

?>