<?php

require_once('globals.inc.php');
require_once('Knb/FrontController.php');

$frontController = new Knb_FrontController($serviceContainer, $g_options['mvc']['debug']);
$frontController->initializeFromServerArray();
if (array_key_exists('_debug_mvc', $_GET) && $g_options['mvc']['debug'])
{
	print("<pre id='debug-mvc'>");
	print("<strong>MVC debug values</strong>\n\n");
	$frontController->printDebugInfos();
	print("</pre>");
}
$frontController->run();

?>