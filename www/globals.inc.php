<?php

function get_base_url()
{
	$url = $_SERVER['SCRIPT_NAME'];
	$urlParts = explode('/', $url);
	array_pop($urlParts);
	return implode('/', $urlParts).'/';
}

function knb_exception_handler($exception)
{
	global $g_options;

	/* For most users we want to display either the configured message if possible or a short error message.
	 */
    if(!(isset($g_options['error']['debug']) && $g_options['error']['debug'] === TRUE))
	{
		if (isset($g_options['error']['redirect'])
			&& $g_options['error']['redirect'] !== FALSE
			&& !headers_sent())
		{
			header('location: ' . $g_options['error']['redirect']);
		}
		else
		{
			echo "An exception has occured, please contact the site administrator";
		}
		exit;
	}

	/* We will use some functions like var_export that don't like for any buffering to be in progress.
	 */
	while (@ob_end_clean());

	$sanitize_func = create_function('&$a', 'if (is_string($a)) { $a = htmlentities($a); }');
	$css = get_base_url() . 'styles/error.css';

	$html = '<html>';
	$html .= '<head><title>Exception</title>';
	$html .= '<link rel="stylesheet" href="'.$css.'" type="text/css"></head>';
	$html .= '<body><h1>'.htmlentities(get_class($exception)).'</h1>';

	$html .= '<p id="message">'.htmlentities($exception->getMessage()).'</p>';
	$html .= '<p>At line <span id="line">'.$exception->getLine().'</span>';
	$html .= ' of <span id="file">'.htmlentities($exception->getFile()).'</span></p>';

	$html .= '<ul id="stacktrace">';
	foreach ($exception->getTrace() as $trace)
	{
		array_walk($trace, $sanitize_func);

		$html .= '<li>';

		$html .= '<div class="caller">';

		$reflectedCaller = null;

		if (isset($trace['class']))
		{
			$html .= '<span class="class">'.$trace['class'].'</span>';
			$html .= '<span class="type">'.$trace['type'].'</span>';

			$reflectedCaller = new ReflectionMethod($trace['class'], $trace['function']);
		}
		else
		{
			try
			{
				$reflectedCaller = new ReflectionFunction($trace['function']);
			}
			catch(ReflectionException $e)
			{
				/* Internal function can't be reflected upon... we may have it one of them.
				 */
			}
		}
		$html .= '<span class="function">'.$trace['function'].'</span><span class="argdef">(';

		if ($reflectedCaller !== null)
		{
			foreach($reflectedCaller->getParameters() as $arg)
			{
				if ($arg->isPassedByReference()) $html .= '&amp;';
				$html .= '$'.htmlentities($arg->getName());
				if ($arg->isDefaultValueAvailable())
				{
					$html .= ' = ';
					$html .= htmlentities(var_export($arg->getDefaultValue(), TRUE));
				}
			}
		}
		else
		{
			$html .= "...";
		}

		$html .= ')</span></div>';

		$html .= '<p class="position">';
		$html .= 'At line <span class="line">'.$trace['line'].'</span> of ';
		$html .= '<span class="file">'.$trace['file'].'</span></p>';

		if (isset($trace['args']) && count($trace['args']))
		{
			$html .= '<p class="args">Argument values : </p>';
			$html .= '<ul class="args">';
			foreach($trace['args'] as $arg)
			{
				$html .= '<li>'.htmlentities(var_export($arg, TRUE)).'</li>';
			}
			$html .= '</ul>';
		}

		$html .= '</li>';
	}
	$html .= '</ul>';
	$html .= '</html>';

	echo $html;
}

set_exception_handler("knb_exception_handler");


if (get_magic_quotes_gpc()) throw new Exception("magic_quotes_gpc is not supported");

/******************************************************************************
 * Constants 
 *****************************************************************************/

define('ROOT_PATH', dirname(__file__).'/');
define('ROOT_URL', get_base_url());
ini_set("include_path", ROOT_PATH . '/libs/');

require_once('Zend/Db/Adapter/Mysqli.php');

/******************************************************************************
 * Loading of the options (default & user)
 *****************************************************************************/

if (!file_exists(ROOT_PATH."options.php"))
	die("The file options.php should exists, please read the instructions inside of 'options.php-example'.");

require_once ROOT_PATH."options_default.php";
require_once ROOT_PATH."options.php";

/******************************************************************************
 * Initialize all the global variables
 *****************************************************************************/

 /*
function __autoload($class_name) {
	require_once(ROOT_PATH . '/libs/' . str_replace('_', '/', $class_name) . '.php');
	
	global $g_options;

	foreach($g_options['autoload'] as $autoload)
	{
		if (preg_match($autoload['regex'], $class_name, $matches))
		{
			$file_name = $autoload['dir'] . $matches[1] . $autoload['ext'];
			if (file_exists($file_name))
			{
				require_once $file_name;
				return;
			}
		}
	}
	throw new Exception('Unable to __autoload class '.$class_name);
	 
}

$g_skins = new Vbf_Skins($g_options['skin']['dir'], $g_options['skin']['url'],
	$g_options['skin']['default']);


$g_database = new Vbf_MySQL($g_options['mysql']['host'], $g_options['mysql']['user'],
	$g_options['mysql']['password'], $g_options['mysql']['database'],
	$g_options['mysql']['port']);
 */

$g_database = new Zend_Db_Adapter_Mysqli($g_options['mysql']);
$g_database->setFetchMode(Zend_Db::FETCH_OBJ);

if (!defined('KNB_NO_DATABASE_ACCESS'))
{
	require_once "Knb/ConnectedUser.php";

	$g_user = new Knb_ConnectedUser();
}
?>
