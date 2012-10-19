<?php
/*
 * This file contain the default values for options and should never be
 * touched when installing. If different values are needed they should be 
 * set in the 'options.php' file.
 */

$g_options = array();

/* Set this option to TRUE to display error and exception details instead of
 * just a short page saying that an error hapenned.
 *
 * Don't let this enabled in prodution as it leak lot of informations to a
 * potential attacker.
 */
$g_options['error']['debug'] = FALSE;

/* This option could either contain the url (as passed to a location header) of
 * a page explaining to the user that an error hapenned or FALSE to let the
 * engine display a small error message.
 */
$g_options['error']['redirect'] = FALSE;
$g_options['mvc']['debug'] = FALSE;

/* By default the minified version of jQuery is used in the knb views, set this
 * option to TRUE to get the standard version.
 */
$g_options['jquery']['debug'] = FALSE;
$g_options['bootsrap']['debug'] = FALSE;

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
$g_options['skin']['mask'] = '#SKIN#';
$g_options['skin']['default'] = 'default';
$g_options['skin']['actual'] = 'default';

$g_options['mysql']['host'] = '127.0.0.1';
$g_options['mysql']['username'] = 'root';
$g_options['mysql']['password'] = '';
$g_options['mysql']['dbname'] = 'knb';
$g_options['mysql']['port'] = 3306;

?>
