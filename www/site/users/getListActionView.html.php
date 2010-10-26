<?php
$this->guardAllParametersHtml();
?>

<ul>
	<?php foreach($this->users as $user):?>
	<li><a href="<?php echo $this->createUriFromModule($user->user_login, true); ?>"><?php echo $user->user_full_name; ?></a></li>
	<?php endforeach; ?>
</ul>