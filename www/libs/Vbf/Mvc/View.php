<?php

require_once('Vbf/Guard.php');

class Vbf_Mvc_View
{
	private $viewFile;
	private $parameters;
	private $frontController;
	
	public function __construct($frontController, $viewFile, $parameters = null)
	{
		$this->frontController = $frontController;
		$this->viewFile = $viewFile;
		if (is_array($parameters))
		{
			$this->parameters = $parameters;
		}
		else if ($parameters == null)
		{
			$this->parameters = array();
		}
		else
		{
			throw new Exception("'parameters' should be null or an array."); 
		}
	}
	
	public function parameterExists($name)
	{
		return array_key_exists($name, $this->parameters);
	}
	
	private function forceParameterType($type, $name)
	{
		if (settype($this->parameters[$name], $type) !== TRUE)
		{
			throw new Exception("Unable to force type $type for parameter $name");
		}
	}
	
	protected function ensureParameterExists($name)
	{
		if (!$this->parameterExists($name))
		{
			throw new Exception("Unknown view parameter : $name");
		}
	}
	
	public function initializeParameter($type, $name)
	{
		$this->ensureParameterExists($name);
		$this->forceParameterType($type, $name);
	}
	
	public function initializeParameterDefault($type, $name, $defaultValue)
	{
		if (!$this->parameterExists($name))
		{
			$this->$name = $defaultValue;
		}
		$this->forceParameterType($type, $name);
	}
	
	public function __get($name)
	{
		$this->ensureParameterExists($name);
		
		return $this->parameters[$name];
	}
	
	public function __set($name, $value)
	{
		$this->parameters[$name] = $value;
	}
	
	public function render()
	{
		ob_start();
		try
		{
			require($this->viewFile);
		}
		catch(Exception $e)
		{
			ob_end_clean();
			throw $e;
		}
		$buffer = ob_get_contents();
		ob_end_clean();
		
		return $buffer;
	}
	
	public function display()
	{
		require($this->viewFile);
	}
		
	public function getSiteView($method, $uri, $acceptHeader)
	{
		ob_start();
		$ft = new Knb_FrontController();
		$ft->initialize($method, $uri, $acceptHeader);
		$ft->run();
		$buffer =  ob_get_clean();
		//ob_end_clean();
		
		return trim($buffer);
	}
	
	/*
	 * To be used from the view
	 */
	
	public function createUriFromResource($uri, $urlencode = false)
	{
		return $this->frontController->createUriFromResource($uri, $urlencode);
	}
	
	public function createUriFromModule($uri, $urlencode = false)
	{
		return $this->frontController->createUriFromModule($uri, $urlencode);
	}	
	
	public function createUriFromBase($uri, $urlencode = false)
	{
		return $this->frontController->createUriFromBase($uri, $urlencode);
	}
	
	public function guardParameters($params, $func, $guardAny = true)
	{
		foreach($params as $param)
		{
			$this->$param = Vbf_Guard::guardAny($this->$param, $func, $guardAny);
		}
	}
	
	public function guardAllParameters($except, $func, $guardAny = true)
	{
		if ($except === NULL) $except = array();
		if (!is_array($except)) $except = array($except);
		
		$keys = array_keys($this->parameters);
		$keys = array_diff($keys, $except);
		$this->guardParameters($keys, $func, $guardAny);
	}
	
	public function guardParametersHtml($params, $guardAny = true)
	{
		$this->guardParameters($params, Vbf_Guard::getgetHtmlentitiesFunc(), $guardAny);
	}
	
	public function guardAllParametersHtml($except = NULL, $guardAny = true)
	{
		$this->guardAllParameters($except, Vbf_Guard::getHtmlentitiesFunc(), $guardAny);
	}
	
	public function guardParametersXml($params, $guardAny = true)
	{
		$this->guardParameters($params, Vbf_Guard::getgetXmlentitiesFunc(), $guardAny);
	}
	
	public function guardAllParametersXml($except = NULL, $guardAny = true)
	{
		$this->guardAllParameters($except, Vbf_Guard::getXmlentitiesFunc(), $guardAny);
	}
}

?>