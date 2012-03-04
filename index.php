<html>
<head>
<title>PhysCIP Door Access</title>
<?php include_once 'conf.inc.php'; ?>
<link rel="stylesheet" href="style.css" />
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="login.js"></script>
<script type="text/javascript" src="websocket.js"></script>
<script type="text/javascript" src="VirtualKeyboard/vk_loader.js?vk_layout=DE%20German&vk_skin=air_large"></script>
</head>
<body onload="wsConnect('ws://<?php echo $_SERVER['HTTP_HOST'] . ':' . websocket_port ?>');">
<div id="login">
<form id="loginform">
<table>
<tr>
<td>User</td>
<td><input type="text" name="user" id="username" onfocus="VirtualKeyboard.attachInput(this)" /></td>
</tr>
<tr>
<td>Password</td>
<td><input type="password" name="password" id="password" onfocus="VirtualKeyboard.attachInput(this)" class="VK_no_animate" /></td>
</tr>
<tr>
<td colspan="2" align="right"><input type="reset" onclick="display_error()" value="Cancel" />
<input type="button" value="Log in" onclick="login()" /></td>
</tr>
</table>
</form>
<div id="keyb"></div>
<script type="text/javascript">
EM.addEventListener(window,'domload',function(){
	VirtualKeyboard.toggle('username','keyb');
});

function setkeybindings()
{
	$('#kb_benter').mouseup(function() {
		login();
		return FALSE;
		});
	$('#kb_btab').mouseup(function() {
		// jump to next field
		switch ($("*:focus").attr("id"))
		{
			case 'username':
				$('#password').focus();
			break;
			case 'password':
				$('#username').focus();
			break;
		}
		// unfocus button
		$('#kb_btab').removeClass("kbButtonHover kbButtonDown");
		// don't execute other handlers
		return false;
		});
}
window.setTimeout("setkeybindings()", 1000);
</script>
</div>
<div id="loggedin" style="display: none">
Currently logged in
<div id="loggedin_name"></div>
<input type="button" onclick="logout()" value="Log out" />
</div>
<input type="button" onclick="update()" value="Update" />
<div id="errormsg" style="display: none"></div>
</body>
</html>