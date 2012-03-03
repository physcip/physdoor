function display_error(err)
{
	if(typeof(err) == "string")
	{
		$('#errormsg').html(err);
		$('#errormsg').show('slow');
	}
	else
	{
		$('#errormsg').hide('slow');
		$('#errormsg').html("");
	}
}

function login()
{
	$.post('login.php?action=login', { user: $('#username').val(), password: $('#password').val() }, 
		function(data)
		{
			if (data.loggedin == true) // success
			{
				$('#username').val("");
				$('#password').val("");
				//update(); happens automatically when we receive our own WS message
				wsSend("login");
			}
			else // display error
			{
				$('#password').val("");
			}
			
			display_error(data.error);
		},
		"json");
}

function logout()
{
	$.get('login.php?action=logout', 
		function(data)
		{
			//update(); happens automatically when we receive our own WS message
			wsSend("logout");
			
			display_error(data.error);
		}
		);
}

function update()
{
	$.get('login.php?action=status',
		function(data)
		{
			if (data.loggedin == true) // somebody is logged in
			{
				$('#loggedin_name').html(data.name);
				$('#loggedin').show('slow');
				$('#login').hide('slow');
				
				$('#username').val("");
				$('#password').val("");
			}
			else
			{
				$('#login').show('slow');
				$('#loggedin').hide('slow');
			}
			
			display_error(data.error);
		},
		"json");
}