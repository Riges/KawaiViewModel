<?php

$result = array();
if ($this->error == null)
{
	$result = array("success" => true);
}
else
{
	$result = array("success" => false, "error" => $this->error->getMessage());
}

echo json_encode($result);

?>