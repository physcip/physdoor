var slTimeout;

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
	$('#loggingin').slideDown('slow');
	$('#keyb').slideUp('slow');
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
				$('#password').focus();
			}
			
			display_error(data.error);
			$('#loggingin').slideUp('slow');
			$('#keyb').slideDown('slow');
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
			
			window.setTimeout("$('#username').focus()", 500);
			
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
				$('#loggedin').slideDown('slow');
				$('#login').slideUp('slow');
				
				$('#username').val("");
				$('#password').val("");
			}
			else
			{
				$('#login').slideDown('slow');
				$('#loggedin').slideUp('slow');
			}
			
			display_error(data.error);
			
			if (master) // only master does screen locking
			{
				screenlock();
			}
		},
		"json");
}

function screenlock()
{
	// re-lock (or re-unlock) screens every 60 seconds in case a client is rebooted or was unplugged
	
	window.clearTimeout(slTimeout);
	
	if ($('#loggedin').css('display') == 'none')
	// nobody is logged in
	{
		$.get('login.php?action=lock');
	}
	else
	// somebody is logged in or login/logout is in progress
	// this gives us a grace period of 60 seconds during which a new person can log in without the screens getting locked
	{
		$.get('login.php?action=unlock');
	}
	
	slTimeout = window.setTimeout("screenlock()", 60000);
}