<?php

require_once('Vbf/Mvc/View.php');
require_once('Vbf/Mvc/Exception404.php');

abstract class Vbf_Mvc_Controller
{
	private $parameters;
	private $views;
	
	private $method;
	protected function getMethod() { return $this->method; }
	
	private $extension;
	protected function getExtension() { return $this->extension; }
	
	private $frontController;
	protected function getFrontController() { return $this->frontController; }
	
	private $modulePath;
	protected function getModulePath() { return $this->modulePath; }
	
	private $moduleName;
	protected function getModuleName() { return $this->moduleName; }
	
	private $actionName;
	protected function getActionName() { return $this->actionName; }
	
	private $methodName;
	protected function getMethodName() { return $this->methodName; }
	
	protected abstract function onBefore();
	protected abstract function onAfter();
	
	public function initialize($frontController, $modulePath, $moduleName, $method, $extension, $actionName, $methodName)
	{
		$this->frontController = $frontController;
		$this->modulePath = $modulePath;
		$this->moduleName = $moduleName;
		$this->method= $method;
		$this->extension = $extension;
		$this->actionName = $actionName;
		$this->methodName = $methodName;
		
		$this->parameters = array();
		$this->autoViewEnabled = ($method == 'GET');
	}	
	
	protected function setViewParameter($view, $parameter, $value)
	{
		$this->parameters[$view][$parameter] = $value;
	}
	
	protected function setParameter($parameter, $value)
	{
		$this->setViewParameter('', $parameter, $value);
	}
	
	protected function setParameters($parametersArray)
	{
		$this->setViewParameters('', $parametersArray);
	}
	
	protected function setViewParameters($view, $parametersArray)
	{
		foreach($parametersArray as $parameter => $value)
		{
			$this->setViewParameter($view, $parameter, $value);
		}
	}	
	
	private function getViewFilePath($view)
	{
		if ($view == '') $view = $this->methodName;
		$path = '';
		if ( (strlen($view) > 0) && ($view[0] == '/') )
		{
			$view = substr($view, 1);
			$path = $this->frontController->getSiteFolder() ;
		}
		else
		{
			$path = $this->modulePath;
		}
		
		return $path . '/' . $view . "View." . $this->extension . ".php";
	}
	
	private function getViewParameters($view)
	{
		if (array_key_exists($view, $this->parameters))
		{
			return $this->parameters[$view];
		}
		else
		{
			return array();
		}
	}
	
	private function getView($view)
	{
		$path = $this->getViewFilePath($view);
		if (!file_exists($path)) throw new Vbf_Mvc_Exception404("File not found: $path");
		$parameters = $this->getViewParameters($view);
		return new Vbf_Mvc_View($this->frontController, $path, $parameters);
	}
	
	protected function renderView($view)
	{
		return $this->getView($view)->render();
	}
	
	protected function displayView($view)
	{
		$this->getView($view)->display();
	}
	/*
	protected function addView($viewName)
	{
		
	}
	
	protected function getView($viewName)
	{
		
	}*/
	
	/**
	 * Redirect the user to an URL.
	 */
	protected function redirectToUrl($url)
	{
		if(array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS']== "on")
			$_SESSION['HTTP_REDIRECT'] = $_SERVER['REQUEST_URI'];
		else
			$_SESSION['HTTP_REDIRECT'] = $_SERVER['REQUEST_URI'];
			//echo $_SERVER['HTTP_REFERER'];
		header('location: ' . $url);
		flush();
		die();
	}
	
	/**
	 * Redirect the user to another page on the site.
	 */
	protected function redirect($uri)
	{
		throw new Exception('Not implemented.');
	} 
	
	/**
	 * Go back to the previous page.
	 */
	protected function back()
	{
		if (!array_key_exists('HTTP_REFERER', $_SERVER))
		{
			throw new Exception('Unable to go back, no referer specified by browser.');
		}
		$this->redirectToUrl($_SERVER['HTTP_REFERER']);
	}
}

?>