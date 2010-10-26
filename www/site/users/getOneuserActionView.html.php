<?php
	global $g_user;
	
	$this->guardAllParametersHtml();
?>
<div id="text">
<p>
	Mail : <a href="mailto:<?php echo $this->user_mail; ?>"><?php echo $this->user_mail; ?></a>
</p>
</div>

<?php if ($g_user->haveOneRight(array('user_delete', 'user_edit'))): ?>
<div id="smallboxbox">
	<div class="smallbox">
		<h2>Administration</h2>
		<div class="text">
			<p>
				Id Utilisateur : <strong><?php echo $this->user_id; ?></strong>
				<?php if ($g_user->haveRight('user_edit')): ?>
				<br /><br />
				<a href="<?php echo $this->createUriFromResource('edit'); ?>">Editer l'utilisateur</a>
				<?php endif; ?>
				<?php if ($g_user->haveRight('user_edit')): ?>
				<br /><br />
				<a href="<?php echo $this->createUriFromResource('delete'); ?>">Suprimer l'utilisateur</a>
				<?php endif; ?>
			</p>
		<div class="bottom"></div></div>
	</div>
</div>
<?php endif; ?>