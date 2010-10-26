<?php
$this->guardAllParametersHtml();
?>
<form method="POST" action="<?php echo $this->createUriFromResource('?_method=PUT');?>">
	<input type="hidden" name="from_html" value="true" ?>
	
	<table class="edit">
		<tr>
			<td class="description">Login :</td>
			<td><input type="text" id="user_login" name="user_login" value="<?php echo $this->user_login;?>" /></td>
		</tr>
		<tr>
			<td class="description">Nom :</td>
			<td><input type="text" id="user_full_name" name="user_full_name" value="<?php echo $this->user_full_name;?>" /></td>
		</tr>
			<tr>
			<td class="description">Adresse mail :</td>
			<td><input type="text" id="user_mail" name="user_mail" value="<?php echo $this->user_mail;?>" /></td>
		</tr>
		<tr>
			<td class="description">Mot de passe :</td>
			<td>
				<input type="password" id="user_password_1" name="user_password_1" value="" />
				<br />
				<input type="password" id="user_password_2" name="user_password_2" value="" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Envoyer" /></td>
	</table>
</form>