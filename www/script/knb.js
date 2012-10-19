function doLogin()
{
	var login = $('#login').val();
	var password = $('#password').val();
	
	if ( (login === undefined) || (password === undefined) || (login === "") || (password === ""))
		return;
	
	$('#loginform :input').attr("disabled", true);
	$.post(root_url + "login.json", { login: login, password: password }, onLoginAjaxResult, "json");
}

function onLoginAjaxResult(data)
{
	if (data.success === true)
		window.location.reload();
	else
	{
		alert(data.error);
		$('#loginform :input').attr("disabled", false);
		$('login').focus();
	}
}