<?php

/**
 * Class Vbf_Mvc_FrontController
 */
class Vbf_Mvc_FrontController
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerBuilder
     */
    private $serviceContainer;

    /**
     * @var bool
     */
    private $isInitialized = FALSE;

    /**
     * @var
     */
    private $charset;

    /**
     * @var string
     */
    private $siteFolder;

    /**
     * @var
     */
    private $baseUri;

    /**
     * @var bool
     */
    private $debugMode;

    /**
     * @var array
     */
    private static $validMethods = array('GET', 'POST', 'PUT', 'DELETE');
    /**
     * @var string
     */
    private static $defaultContentType = 'text/plain';
    /**
     * @var array
     */
    private static $extensionContentType = array(
        'html' => array('application/xhtml+xml', 'text/html'), // RFC3236 & RFC2854
        'txt' => array('text/plain'), // RFC3676
        'xml' => array('application/xml'), // RFC3023
        'json' => array('application/json'), // RFC4627
        'modal' => array('application/xhtml+xml', 'text/html') // RFC3236 & RFC2854
    );

    /**
     * @var
     */
    private $webserver_method;
    /**
     * @var
     */
    private $webserver_acceptHeader;
    /**
     * @var
     */
    private $webserver_uri;

    /**
     * @var
     */
    private $method;
    /**
     * @var
     */
    private $relativeUri;
    /**
     * @var
     */
    private $parsedUri;
    /**
     * @var
     */
    private $extension;
    /**
     * @var
     */
    private $contentType;
    /**
     * @var
     */
    private $dispatchResult;

    /**
     * @var
     */
    private $controllerInstance;
    /**
     * @var
     */
    private $actionMethod;
    /**
     * @var
     */
    private $actionMethodName;

    /**
     * @param $siteFolder
     * @param $baseUri
     * @param $charset
     * @param $debugMode
     * @param null $serviceContainer
     */
    public function __construct($siteFolder, $baseUri, $charset, $debugMode, $serviceContainer = null)
    {
        $this->charset = $charset;
        $this->debugMode = ($debugMode === TRUE);
        $this->siteFolder = Vbf_Path::rtrimSlashes($siteFolder);
        $this->baseUri = $baseUri;

        if ($serviceContainer !== null) {
            $this->serviceContainer = $serviceContainer;
        }
    }

    public function getServiceContainer(){
        return $this->serviceContainer;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->isInitialized;
    }

    /**
     * @return string
     */
    public function getSiteFolder()
    {
        return $this->siteFolder;
    }

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
        return Vbf_Path::combineAlways($this->baseUri, $this->dispatchResult['moduleUri']);
    }

    /**
     * Get the URI of the current resource.

     */
    public function formatUri($uri, $urlencode = false)
    {
        global $g_options;
        $search = array();
        $replace = array();

        if (isset($g_options['skin']['mask'])) {
            if (isset($g_options['skin']['url'])) {
                $search[] = $g_options['skin']['mask'];
                $replace[] = $g_options['skin']['url'] . $g_options['skin']['actual'];
            } else {
                $search[] = $g_options['skin']['mask'] . '/';
                $replace[] = '';
            }
        }
        $uri = str_replace($search, $replace, $uri);
        if ($urlencode) $uri = urlencode($uri);
        return $uri;
    }

    /**
     * @return string
     */
    public function getCurrentResourceUri()
    {
        return Vbf_Path::combineAlways($this->baseUri, $this->dispatchResult['resourceUri']);
    }

    /**
     * @param $uri
     * @param bool $urlencode
     * @return string
     */
    public function createUriFromResource($uri, $urlencode = false)
    {
        $uri = $this->formatUri($uri, $urlencode);
        return Vbf_Path::combineWithAlternateRoot($this->getCurrentResourceUri(), $uri, $this->baseUri);
    }

    /**
     * @param $uri
     * @param bool $urlencode
     * @return string
     */
    public function createUriFromModule($uri, $urlencode = false)
    {
        $uri = $this->formatUri($uri, $urlencode);
        return Vbf_Path::combineWithAlternateRoot($this->getCurrentModuleUri(), $uri, $this->baseUri);
    }

    /**
     * @param $uri
     * @param bool $urlencode
     * @return string
     */
    public function createUriFromBase($uri, $urlencode = false)
    {
        $uri = $this->formatUri($uri, $urlencode);
        return Vbf_Path::combineWithAlternateRoot($this->getBaseUri(), $uri, $this->baseUri);
    }

    /**
     * @param $path
     * @param $moduleName
     * @param $suffix
     * @return ReflectionClass
     */
    private function getModuleReflectionClass($path, $moduleName, $suffix)
    {
        $baseName = $moduleName . $suffix;

        $filePath = "{$this->siteFolder}/$path/$baseName.php";
        require_once($filePath);
        return new ReflectionClass($baseName);
    }

    /**
     * @param $path
     * @param $moduleName
     * @return object
     */
    private function getDispatcherInstance($path, $moduleName)
    {
        return $this->getModuleReflectionClass($path, $moduleName, '_Dispatcher')->newInstance();
    }

    /**
     * @param $path
     * @param $moduleName
     * @return ReflectionClass
     */
    private function getControllerReflectionClass($path, $moduleName)
    {
        return $this->getModuleReflectionClass($path, $moduleName, '_Controller');
    }

    /**
     * @param $pathArray
     * @param array $currentPathArray
     * @param array $parameters
     * @param array $moduleUriArray
     * @param array $resourceUriArray
     * @return array|null
     * @throws Exception
     */
    private function resolvePath($pathArray, $currentPathArray = array(), $parameters = array(), $moduleUriArray = array(), $resourceUriArray = array())
    {
        $currentPathString = implode('/', $currentPathArray);
        $moduleName = (count($currentPathArray) > 0) ? end($currentPathArray) : 'root';
        $dispatcher = $this->getDispatcherInstance($currentPathString, $moduleName);
        $rules = $dispatcher->getDispatchRules();

        $pathArraylength = count($pathArray);
        foreach ($rules as $rule) {
            $type = $rule['type'];
            // If the rule is an action is the method OK ?
            if (($type == 'action') && ($rule['method'] != $this->method)) continue;

            $url = $rule['url'];
            $paramCount = $rule['params'];

            $elementUsed = $paramCount + (($url == '') ? 0 : 1);

            // Is there enough path elements to consume ?
            if ($pathArraylength < $elementUsed) continue;

            // Is the name of the action/module the one we seek ?
            $urlInPath = '';
            if (array_key_exists($paramCount, $pathArray)) {
                $urlInPath = $pathArray[$paramCount];
            }
            if ($urlInPath != $url) continue;

            // Extract the parameters if there are some
            $currentParameters = array();
            if ($paramCount > 0) {
                $currentParameters = array_slice($pathArray, 0, $paramCount);
                $parameters = array_merge($parameters, $currentParameters);
            }

            if ($type == 'module') {
                array_push($currentPathArray, $rule['name']);
                array_push($moduleUriArray, end($currentPathArray));
                array_push($resourceUriArray, end($currentPathArray));
                if ($paramCount > 0) {
                    $moduleUriArray = array_merge($moduleUriArray, $currentParameters);
                    $resourceUriArray = array_merge($resourceUriArray, $currentParameters);
                }

                return $this->resolvePath(array_slice($pathArray, $elementUsed),
                    $currentPathArray, $parameters, $moduleUriArray, $resourceUriArray);
            } else if ($type == 'action') {
                if ($pathArraylength - $elementUsed != 0) return NULL;

                if ($paramCount > 0) {
                    $resourceUriArray = array_merge($resourceUriArray, $currentParameters);
                }

                foreach ($parameters as $name => $value) {
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
            } else {
                throw new Exception("Unknown type : $type");
            }
        }
    }

    /**
     * @throws
     * @throws Exception
     */
    public function run()
    {
        if ($this->isInitialized !== TRUE) {
            throw new Exception("Unitialized FrontController instance.");
        }

        try {
            if (!$this->dispatchSuccess) throw $this->dispatchException;

            $this->controllerInstance->initialize($this, $this->dispatchResult['modulePath'], $this->dispatchResult['moduleName'], $this->method, $this->extension, $this->dispatchResult['actionName'], $this->actionMethodName);

            header("Content-type: {$this->contentType}; charset={$this->charset}");
            $this->controllerInstance->onBefore();
            $this->actionMethod->invokeArgs($this->controllerInstance, $this->dispatchResult['parameters']);
            $this->controllerInstance->onAfter();
        } catch (Vbf_Mvc_Exception404 $exception) {
            $this->on404();
        }
    }

    /**
     * Basic parsing of the URI and initialize all the fields linked to it.

     */
    private function initializeUri()
    {
        // Separate the uri in different composants
        $this->parsedUri = parse_url($this->webserver_uri);

        $extensionRegex = '/(.*)\.([^.\/]*)$/';
        if (preg_match($extensionRegex, $this->parsedUri['path'], $matches)) {
            $this->parsedUri['path'] = $matches[1];
            $this->parsedUri['extension'] = $matches[2];
        } else {
            $this->parsedUri['extension'] = NULL;
        }

        $relativeUriRegex = '/^' . preg_quote($this->baseUri, '/') . '(.*)$/';
        $this->relativeUri = preg_replace($relativeUriRegex, '\1', $this->parsedUri['path']);
        $this->relativeUri = Vbf_Path::rtrimSlashes($this->relativeUri);
    }

    /**
     * Find the extension that will be used from the "Accept" header send by
     * the user agent and if present the extension in the URI.

     */
    private function initializeExtension()
    {
        $this->extension = $this->parsedUri['extension'];

        if ($this->extension == NULL || $this->extension == 'htm') {
            //FIXME: Use the accept header to select one.
            $this->extension = 'html';
        }

        if (!array_key_exists($this->extension, self::$extensionContentType)) {
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
        if (array_key_exists('_method', $_GET)) {
            $this->method = $_GET['_method'];
        }

        $this->method = strtoupper($this->method);

        if (!in_array($this->method, self::$validMethods)) {
            throw new Exception("Unknown method : {$this->method}.");
        }
    }

    /**
     *
     */
    private function dispatch()
    {
        $this->dispatchException = NULL;
        $this->dispatchSuccess = FALSE;

        try {
            // Find the module (and his associated controller) along with the action
            $this->dispatchResult = $this->resolvePath(Vbf_Path::explode($this->relativeUri));
            if ($this->dispatchResult === NULL) throw new Vbf_Mvc_Exception404("No module or action is matching this path ({$this->relativeUri}).");

            // Instantiate the controller
            $controllerReflectionClass = $this->dispatchResult['controller'];
            $this->controllerInstance = $controllerReflectionClass->newInstance($this->serviceContainer);

            // Find the action method
            $this->actionMethodName = strtolower($this->method) . ucfirst($this->dispatchResult['actionName']) . 'Action';
            $this->actionMethod = $controllerReflectionClass->getMethod($this->actionMethodName);

            $this->dispatchSuccess = TRUE;
        } catch (Exception $exception) {
            $this->dispatchException = $exception;
        }
    }

    /**
     * @param $method
     * @param $uri
     * @param $acceptHeader
     */
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
        if (array_key_exists('HTTP_ACCEPT', $_SERVER)) {
            $httpAccept = $_SERVER['HTTP_ACCEPT'];
        }
        $this->initialize($_SERVER["REQUEST_METHOD"], $_SERVER['REQUEST_URI'], $httpAccept);
    }

    /**
     *
     */
    public function on404()
    {
        header("HTTP/1.0 404 Not Found", true, 404);
        echo "<html><head><title>404, Not Found.</title></head><body><h1>404Error </h1><p>The page cannot be found.</p>";
    }

    /**
     *
     */
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
        print("parsedUri = ");
        print_r($this->parsedUri);
        print("\n");
        print("extension = " . $this->extension . "\n");
        print("contentType = " . $this->contentType . "\n");

        print("dispatchSuccess = " . ($this->dispatchSuccess ? 'TRUE' : 'FALSE') . "\n");
        print("dispatchResult = ");
        print_r($this->dispatchResult);
        print("\n");
        print("dispatchException = " . $this->dispatchException . "\n");
    }
}

