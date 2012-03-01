function login()
{
	$.post('login.php?action=login', { user: $('#username').val(), password: $('#password').val() }, 
	function(data)
	{
		if (data == true) // success
		{
			$('#username').val("");
			$('#password').val("");
			update();
		}
		else // display error
		{
			$('#password').val("");
			$('#errormsg').html(data.error);
		}
	},
	"json");
}

function logout()
{
	$.get('login.php?action=logout', update());
}

function update()
{
	$.get('login.php?action=status',
		function(data)
		{
			if (data != false) // somebody is logged in
			{
				$('#loggedin_name').html(data.name);
				$('#loggedin').show('slow');
				$('#login').hide('slow');
			}
			else
			{
				$('#errormsg').html("");
				
				$('#login').show('slow');
				$('#loggedin').hide('slow');
			}
		},
		"json");
}