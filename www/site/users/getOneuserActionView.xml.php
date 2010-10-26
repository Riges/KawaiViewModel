<?php
$this->guardAllParametersXml();
$avatarPath = "";
$avatarLocalPath = "upload/avatar/".$this->user_id.".jpg";
if (file_exists(ROOT_PATH.$avatarLocalPath))
{
	$avatarPath = ROOT_URL.$avatarLocalPath;
}
?>
<user>
	<login><?php echo $this->user_login; ?></login>
	<name><?php echo $this->user_full_name; ?></name>
	<avatar><?php echo $avatarPath; ?></avatar>
	<mail><?php echo $this->user_mail; ?></mail>
</user>
