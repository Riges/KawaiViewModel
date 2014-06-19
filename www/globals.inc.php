<?php

function get_base_url()
{
    $url = $_SERVER['SCRIPT_NAME'];
    $urlParts = explode('/', $url);
    array_pop($urlParts);
    return implode('/', $urlParts) . '/';
}

if (get_magic_quotes_gpc()) throw new Exception("magic_quotes_gpc is not supported");

/******************************************************************************
 * Constants
 *****************************************************************************/

define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('ROOT_URL', get_base_url());

ini_set("include_path", ROOT_PATH . 'libs' . DIRECTORY_SEPARATOR);

$loader = require_once ROOT_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
use Symfony\Component\ClassLoader\UniversalClassLoader;

$universalLoader = new UniversalClassLoader();
$universalLoader->useIncludePath(true);
$universalLoader->register();

require_once('Zend/Db/Adapter/Mysqli.php');

/******************************************************************************
 * Loading of the options (default & user)
 *****************************************************************************/

if (!file_exists(ROOT_PATH . "options.php"))
    die("The file options.php should exists, please read the instructions inside of 'options.php-example'.");

require_once ROOT_PATH . "options_default.php";
require_once ROOT_PATH . "options.php";

/******************************************************************************
 * Initialize all the global variables
 *****************************************************************************/
require_once ROOT_PATH . '/services.php';

$g_database = new Zend_Db_Adapter_Mysqli($g_options['mysql']);
$g_database->setFetchMode(Zend_Db::FETCH_OBJ);
$g_database->query("SET NAMES utf8");

if (!defined('KNB_NO_DATABASE_ACCESS')) {

    $g_user = $serviceContainer->get('connectedUser');
}
?>
