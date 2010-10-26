<html>
<head><title>Overwrite database with test data</title><head>
<body>
<ul>
<?php

function multi_query_ignore($con, $sql)
{
	$i = 1;
	if ($con->multi_query($sql))
	{
		do
		{
			$con->store_result();
			$i++;
		} while ($con->next_result());
	}
	if ($con->errno !== 0)
	{
		echo " <font color='red'><b>Error in statement n�$i</b></font> :</br>";
		echo "<pre style='white-space: pre-wrap'>{$con->error}</pre></li>";
		die();
	}
	else
	{
		echo " <font color='green'><b>Ok</b></font>.";
	}
}

echo "<li>Include knb libs...";
define('KNB_NO_DATABASE_ACCESS', true);
require_once('../globals.inc.php');
echo " <font color='green'><b>Ok</b></font>.</li>";

echo "<li>Connect to the database.</li>";
$con = new mysqli($g_options['mysql']['host'],
	$g_options['mysql']['username'],
	$g_options['mysql']['password']);
$mysql_db = $g_options['mysql']['dbname'];

echo "<li>DROP/CREATE the dB.</li>";
$con->query("DROP DATABASE IF EXISTS $mysql_db");
$con->query("CREATE DATABASE $mysql_db DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci");
$con->select_db($mysql_db);

echo "<li>Send the structure...";
multi_query_ignore($con, file_get_contents("structure.sql"));
echo "</li>";

echo "<li>Send the basic data...";
multi_query_ignore($con, file_get_contents("data.sql"));
echo "</li>";

echo "<li>Send the test data...";
multi_query_ignore($con, file_get_contents("test_data.sql"));
echo "</li>";

echo "<li>All done !</li>";
?>
</ul>
</body>
