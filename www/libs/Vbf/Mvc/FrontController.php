<?php

require_once('Vbf/Path.php');
require_once('Vbf/Mvc/Dispatcher.php');
require_once('Vbf/Mvc/Controller.php');
require_once('Vbf/Mvc/Exception404.php');

class Vbf_Mvc_FrontController
{
	private $isInitialized = FALSE;
	public function isInitialized() { return $isInitialized; }
	
	private $charset;
	
	private $siteFolder;
	public function getSiteFolder() { return $this->siteFolder; }
	
	private $baseUri;
	
	/**
	 * Get the URI of the root of the website.  
	 */
	public function getBaseUri()
	{
		return $this->baseUri;
	}
	
	/**
	 * Get the current URI.
	 */
	public function getCurrentUri()
	{
		return $this->parsedUri['path'];
	}
	
	/**
	 * Get the URI of the current module.
	 */
	public function getCurrentModuleUri()
	{
		return Path::combineAlways($this->baseUri, $this->dispatchResult['moduleUri']);
	}
	
	/**
	 * Get the URI of the current resource.
	 */
	public function getCurrentResourceUri()
	{
		return Path::combineAlways($this->baseUri, $this->dispatchResult['resourceUri']);
	}
	
	public function createUriFromResource($uri, $urlencode = false)
	{
		if ($urlencode) $uri = urlencode($uri);
		return Path::combineWithAlternateRoot($this->getCurrentResourceUri(), $uri, $this->baseUri);
	}
	
	public function createUriFromModule($uri, $urlencode = false)
	{
		if ($urlencode) $uri = urlencode($uri);
		return Path::combineWithAlternateRoot($this->getCurrentModuleUri(), $uri, $this->baseUri);
	}	
	
	public function createUriFromBase($uri, $urlencode = false)
	{
		if ($urlencode) $uri = urlencode($uri);
		return Path::combineWithAlternateRoot($this->getBaseUri(), $uri, $this->baseUri);
	}	
	
	private $debugMode;
	
	private static $validMethods = array('GET', 'POST', 'PUT', 'DELETE');
	private static $defaultContentType = 'text/plain';
	private static $extensionContentType = array(
		'html' => array('application/xhtml+xml', 'text/html'), // RFC3236 & RFC2854
		'txt' => array('text/plain'), // RFC3676
		'xml' => array('application/xml'), // RFC3023
		'json' => array('application/json') // RFC4627
		);
		
	public function __construct($siteFolder, $baseUri, $charset, $debugMode)
	{
		$this->charset = $charset;
		$this->debugMode = ($debugMode === TRUE);
		$this->siteFolder = Path::rtrimSlashes($siteFolder);
		$this->baseUri = $baseUri;
	}
	
	private function getModuleReflectionClass($path, $moduleName, $suffix)
	{
		$baseName = $moduleName . $suffix;
		
		$filePath = "{$this->siteFolder}/$path/$baseName.php";
		require_once($filePath);
		return new ReflectionClass($baseName);
	}
	
	private function getDispatcherInstance($path, $moduleName)
	{
		return $this->getModuleReflectionClass($path, $moduleName,
			'_Dispatcher')->newInstance();
	}
	
	private function getControllerReflectionClass($path, $moduleName)
	{
		return $this->getModuleReflectionClass($path, $moduleName,
			'_Controller');
	}
	
	private function resolvePath($pathArray, $currentPathArray = array(), $parameters = array(),
		$moduleUriArray = array(), $resourceUriArray = array())
	{
		$currentPathString = implode('/', $currentPathArray);
		$moduleName = (count($currentPathArray) > 0) ? end($currentPathArray) : 'root';
		$dispatcher = $this->getDispatcherInstance($currentPathString, $moduleName);
		$rules = $dispatcher->getDispatchRules();
		
		$pathArraylength = count($pathArray);
		foreach($rules as $rule)
		{
			$type = $rule['type'];
			// If the rule is an action is the method OK ?
			if ( ($type == 'action') && ($rule['method'] != $this->method) ) continue;
			
			$url = $rule['url'];
			$paramCount = $rule['params'];
			
			$elementUsed = $paramCount + ( ($url == '') ? 0 : 1 );
			
			// Is there enough path elements to consume ?
			if ($pathArraylength < $elementUsed) continue;
			
			// Is the name of the action/module the one we seek ?
			$urlInPath = '';
			if (array_key_exists($paramCount, $pathArray))
			{
				$urlInPath = $pathArray[$paramCount];
			}
			if ($urlInPath != $url) continue;
			
			// Extract the parameters if there are some
			$currentParameters = array();
			if ($paramCount > 0)
			{
				$currentParameters = array_slice($pathArray, 0, $paramCount);
				$parameters = array_merge($parameters, $currentParameters);
			}
			
			if ($type == 'module')
			{
				array_push($currentPathArray, $rule['name']);
				array_push($moduleUriArray, end($currentPathArray));
				array_push($resourceUriArray, end($currentPathArray));
				if ($paramCount > 0)
				{
					$moduleUriArray = array_merge($moduleUriArray, $currentParameters);
					$resourceUriArray = array_merge($resourceUriArray, $currentParameters);
				}
				
				return $this->resolvePath(array_slice($pathArray, $elementUsed),
					$currentPathArray, $parameters, $moduleUriArray, $resourceUriArray);
			}
			else if($type == 'action')
			{
				if ($pathArraylength - $elementUsed != 0) return NULL;
				
				if ($paramCount > 0)
				{
					$resourceUriArray = array_merge($resourceUriArray, $currentParameters);
				}
				
				foreach($parameters as $name => $value)
				{
					$parameters[$name] = urldecode($value);
				}
								
				return array(
					'modulePath' => "{$this->siteFolder}/$currentPathString",
					'moduleName' => $moduleName,
					'parameters' => $parameters,
					'moduleUri' => '/' . implode($moduleUriArray, '/'),
					'resourceUri' => '/' . implode($resourceUriArray, '/'),
					'controller' => $this->getControllerReflectionClass($currentPathString, $moduleName),
					'actionName' => $rule['name']
					);
			}
			else
			{
				throw new Exception("Unknown type : $type"); 
			} 
		}
	}
	
	public function run()
	{
		if ($this->isInitialized !== TRUE)
		{
			throw new Exception("Unitialized FrontController instance.");
		}
		
		try
		{
			if (!$this->dispatchSuccess) throw $this->dispatchException;
			
			$this->controllerInstance->initialize($this,
				$this->dispatchResult['modulePath'], $this->dispatchResult['moduleName'],
				$this->method, $this->extension, $this->dispatchResult['actionName'],
				$this->actionMethodName);
			
			header("Content-type: {$this->contentType}; charset={$this->charset}");
			$this->controllerInstance->onBefore();
			$this->actionMethod->invokeArgs($this->controllerInstance, $this->dispatchResult['parameters']);
			$this->controllerInstance->onAfter();
		}
		catch(Vbf_Mvc_Exception404 $exception)
		{
			$this->on404();
		}
	}
	
	private $webserver_method;
	private $webserver_acceptHeader;
	private $webserver_uri;
	
	private $method;
	private $relativeUri;
	private $parsedUri;
	private $extension;
	private $contentType;
	private $dispatchResult;
	
	private $controllerInstance;
	private $actionMethod;
	private $actionMethodName;

	/**
	 * Basic parsing of the URI and initialize all the fields linked to it.
	 */
	private function initializeUri()
	{
		// Separate the uri in different composants
		$this->parsedUri = parse_url($this->webserver_uri);
		
		$extensionRegex = '/(.*)\.([^.\/]*)$/';
		if (preg_match($extensionRegex, $this->parsedUri['path'], $matches))
		{
			$this->parsedUri['path'] = $matches[1];
			$this->parsedUri['extension'] = $matches[2];
		}
		else
		{
			$this->parsedUri['extension'] = NULL;
		}
		
		$relativeUriRegex = '/^'.preg_quote($this->baseUri, '/').'(.*)$/';
		$this->relativeUri = preg_replace($relativeUriRegex, '\1', $this->parsedUri['path']);
		$this->relativeUri = Path::rtrimSlashes($this->relativeUri);
	}
	
	/**
	 * Find the extension that will be used from the "Accept" header send by
	 * the user agent and if present the extension in the URI. 
	 */
	private function initializeExtension()
	{
		$this->extension = $this->parsedUri['extension'];
		
		if ($this->extension == NULL || $this->extension == 'htm')
		{
			//FIXME: Use the accept header to select one.
			$this->extension = 'html';
		}
		
		if (!array_key_exists($this->extension, self::$extensionContentType))
		{
			throw new Exception("Unknown extension : {$this->extension}.");  
		}
		
		//FIXME: Use the accept header to select a content type.
		$this->contentType = 'text/html';
	}
	
	/**
	 * Find the method that was requested by the user it mainly come from the
	 * method the browser sent, but could come from a special '_method' query
	 * argument (Because browsers don't support methods other than GET and
	 * POST)
	 */
	private function initializeMethod()
	{
		$this->method = $this->webserver_method;
		if (array_key_exists('_method', $_GET))
		{
			$this->method = $_GET['_method'];
		}
		
		$this->method = strtoupper($this->method);
		
		if (!in_array($this->method, self::$validMethods))
		{
			throw new Exception("Unknown method : {$this->method}.");
		}
	}
	
	private function dispatch()
	{
		$this->dispatchException = NULL;
		$this->dispatchSuccess = FALSE;
		
		try
		{
			// Find the module (and his associated controller) along with the action
			$this->dispatchResult = $this->resolvePath(Path::explode($this->relativeUri));
			if ($this->dispatchResult === NULL) throw new Vbf_Mvc_Exception404("No module or action is matching this path ({$this->relativeUri}).");
			
			// Instantiate the controller
			$controllerReflectionClass = $this->dispatchResult['controller'];
			$this->controllerInstance = $controllerReflectionClass->newInstance();
			
			// Find the action method
			$this->actionMethodName = strtolower($this->method) . ucfirst($this->dispatchResult['actionName']) . 'Action';
			$this->actionMethod = $controllerReflectionClass->getMethod($this->actionMethodName);			
			
			$this->dispatchSuccess = TRUE;
		}
		catch(Exception $exception)
		{
			$this->dispatchException = $exception;
		}
	}
	
	public function initialize($method, $uri, $acceptHeader)
	{
		$this->isInitialized = FALSE;
		
		$this->webserver_method = $method;
		$this->webserver_uri = $uri;
		$this->webserver_acceptHeader = $acceptHeader;
		
		$this->initializeUri();
		$this->initializeExtension();
		$this->initializeMethod();
		
		$this->dispatch();
		
		$this->isInitialized = TRUE;
	}	
	
	/**
	 * Initialize all parameters from the "magic" $_SERVER array.
	 */
	public function initializeFromServerArray()
	{
		$httpAccept = '';
		if (array_key_exists('HTTP_ACCEPT', $_SERVER))
		{
			$httpAccept = $_SERVER['HTTP_ACCEPT'];
		}
		$this->initialize($_SERVER["REQUEST_METHOD"],
			$_SERVER['REQUEST_URI'],
			$httpAccept);
	}	
	
	public function on404()
	{
		header("HTTP/1.0 404 Not Found", true, 404);
		echo "<html><head><title>404, Not Found.</title></head><body><h1>404Error </h1><p>The page cannot be found.</p>";
	}
	
	public function printDebugInfos()
	{
		print("isInitialized = " . ($this->isInitialized ? 'TRUE' : 'FALSE') . "\n");
		print("debugMode = " . ($this->debugMode ? 'TRUE' : 'FALSE') . "\n");
		print("siteFolder = " . $this->siteFolder . "\n");
		print("baseUri = " . $this->baseUri . "\n");
		
		print("webserver_method = " . $this->webserver_method . "\n");
		print("webserver_acceptHeader = " . $this->webserver_acceptHeader . "\n");
		print("webserver_uri = " . $this->webserver_uri . "\n");
		
		print("method = " . $this->method . "\n");
		print("relativeUri = " . $this->relativeUri . "\n");
		print("parsedUri = "); print_r($this->parsedUri); print("\n");
		print("extension = " . $this->extension . "\n");
		print("contentType = " . $this->contentType . "\n");
		
		print("dispatchSuccess = " . ($this->dispatchSuccess ? 'TRUE' : 'FALSE') . "\n");
		print("dispatchResult = "); print_r($this->dispatchResult); print("\n");
		print("dispatchException = " . $this->dispatchException . "\n");
	}
}

?>
