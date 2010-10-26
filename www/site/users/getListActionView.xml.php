<users>
<?php
$this->guardAllParametersXml();

foreach($this->users as $user):
	$avatarPath = "";
	$avatarLocalPath = "upload/avatar/".$user->user_id.".jpg";
	if (file_exists(ROOT_PATH.$avatarLocalPath))
	{
		$avatarPath = ROOT_URL.$avatarLocalPath;
	}
?>
<user
	login="<?php echo $user->user_login; ?>"
	url="<?php echo $this->createUriFromModule($user->user_login); ?>"
	name="<?php echo $user->user_full_name; ?>"
	mail="<?php echo $user->user_mail; ?>"
	avatar="<?php echo $avatarPath; ?>"
	/>
<?php endforeach; ?>
</users>
