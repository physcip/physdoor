<html>
<head>
<title>PhysCIP Door Access</title>
<?php include_once 'conf.inc.php'; ?>
<link rel="stylesheet" href="style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="login.js"></script>
<script type="text/javascript">
<?php if (gethostbyaddr($_SERVER['REMOTE_ADDR']) == master_name) { ?>
var master = true;
<?php } else { ?>
var master = false;
<?php } ?>
<?php if (!in_array(gethostbyaddr($_SERVER['REMOTE_ADDR']), $outside_names)) { ?>
var showloggedinuser = true;
<?php } else { ?>
var showloggedinuser = false;
<?php } ?>
</script>
<?php if (in_array(gethostbyaddr($_SERVER['REMOTE_ADDR']), $notouch_names)) { ?>
<link rel="stylesheet" href="style-notouch.css" />
<?php }?>
<script type="text/javascript" src="websocket.js"></script>
</head>
<body onload="wsConnect('ws://<?php echo $_SERVER['HTTP_HOST'] . ':' . websocket_port ?>');">
<div id="title">CIP Pool Physik</div>
<div id="login">
<div id="loginoutside">
Please log in at the touchscreen outside.
</div>
<form id="loginform">
<table>
<tr class="formrow">
<td class="formlabel">User</td>
<td class="forminput"><input type="text" name="user" id="username" /></td>
</tr>
<tr class="formrow">
<td class="formlabel">Password</td>
<td class="forminput"><input type="password" name="password" id="password" /></td>
</tr>
<tr>
<td colspan="2" align="right"><input type="reset" id="cancelbutton" onclick="display_error()" value="Cancel" />
<input type="button" id="loginbutton" value="Log in" onclick="login()" /></td>
</tr>
</table>
</form>
</div>
<div id="loggingin" style="display: none">
Logging in...
</div>
<div id="loggedin" style="display: none">
Currently logged in:
<div id="loggedin_name"></div>
<div id="please_logout">
Please log out <span id="logoutoutside">at the touchscreen outside</span> when you leave.<br />
You are responsible for anything that happens in the CIP Pool while you are logged in!
</div>
<input type="button" id="logoutbutton" onclick="logout()" value="Log out" />
</div>
<input type="button" id="updatebutton" onclick="update()" value="Update" />
<div id="errormsg" style="display: none"></div>
</body>
</html>
