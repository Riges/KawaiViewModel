<?php
/*
 * This file contain the default values for options and should never be
 * touched when installing. If different values are needed they should be 
 * set in the 'options.php' file.
 */

$g_options = array();

$g_options['mvc']['debug'] = FALSE;
$g_options['jquery']['debug'] = FALSE;

$g_options['autoload'][] = array(
	'regex' => '/(.*)/',
	'dir' => ROOT_PATH . 'libs/',
	'ext' => '.class.php');
$g_options['autoload'][] = array(
	'regex' => '/Zend_(.*)/',
	'dir' => ROOT_PATH . 'libs/Zend/',
	'ext' => '.php');

$g_options['skin']['dir'] =  ROOT_PATH . 'skins/';
$g_options['skin']['url'] = ROOT_URL . 'skins/';
$g_options['skin']['default'] = 'default';

$g_options['mysql']['host'] = '127.0.0.1';
$g_options['mysql']['username'] = 'root';
$g_options['mysql']['password'] = '';
$g_options['mysql']['dbname'] = 'knb';
$g_options['mysql']['port'] = 3306;

?>
