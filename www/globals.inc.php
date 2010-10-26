<?php

function get_base_url()
{
	$url = $_SERVER['SCRIPT_NAME'];
	$urlParts = explode('/', $url);
	array_pop($urlParts);
	return implode('/', $urlParts).'/';
}

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
