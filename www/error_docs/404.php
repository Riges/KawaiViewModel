<?php
//TODO: Detect application/xml support and send it if possible.
header('Content-Type: text/html; charset=utf-8');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>404 Not found</title>
		<style type="text/css">/*<![CDATA[*/
		body
		{
			background-color: black;
			color : white;
			text-align: center;
			font-family: verdana, arial, helvetica, sans-serif;
			font-variant: small-caps;
		}
		#uri
		{
			font-variant: normal;
			color: #BBB;
		}
		#comment
		{
			font-style: italic;
			font-size: small;
		}
		/*]]>*/</style>
		
	</head>
	<body>
	<h1>404 Not found</h1>
		<p id="picture">
			<img src="<?php echo (defined('ROOT_URL')) ? constant('ROOT_URL') : '/'; ?>error_docs/death.png" alt="Death from the discworld universe (along with a neko)" />
		</p>
		<p id="error">
		The requested URL <span id="uri"><?php echo $_SERVER["REQUEST_URI"]; ?></span> was not found on this server.
		</p>
<?php
global $frontController;
if (isset($frontController))
{
	global $g_options;
	if ($g_options['mvc']['debug'])
	{
		echo "<pre style='text-align:left'>";
		$frontController->printDebugInfos();
		echo "</pre>";
	}
}
?>
		<p id="comment">
			I am death, not taxes. I turn up only once.
		</p>
	</body>
</html>