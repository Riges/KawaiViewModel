function doLogin()
{
	var login = $('login').value;
	var password = $('password').value;
	
	if ( (login == undefined) || (password == undefined)
		|| (login == "") || (password == ""))
	{
		return;
	}
	
	jQuery('#loginform :input').attr("disabled", true);
	jQuery.post(root_url + "login.json", { login: login, password: password }, onLoginAjaxResult, "json");
}

function onLoginAjaxResult(data)
{
	if (data.success == true)
	{
		window.location.reload();
	}
	else
	{
		alert(data.error);
		jQuery('#loginform :input').attr("disabled", false);
		$('login').focus();
	}
}