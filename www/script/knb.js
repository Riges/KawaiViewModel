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

function mysqlDateTimeToJsDate(date) {
	// Split timestamp into [ Y, M, D, h, m, s ]
	var t = date.split(/[- :]/);
	// Apply each element to the Date function
	if(t.length = 3)
		t.push(0, 0, 0);

	return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
}

//JS date time local : Date.toLocaleString();
//JS date local : Date.toLocaleDateString();
//JS time local : Date.toLocaleTimeString();