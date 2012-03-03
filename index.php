<html>
<head>
<title>PhysCIP Door Access</title>
<?php include_once 'conf.inc.php'; ?>
<link rel="stylesheet" href="style.css" />
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="login.js"></script>
<script type="text/javascript" src="websocket.js"></script>
</head>
<body onload="update(); wsConnect('ws://<?php echo $_SERVER['HTTP_HOST'] . ':' . websocket_port ?>');">
<div id="login">
<form id="loginform">
<table>
<tr>
<td>User</td>
<td><input type="text" name="user" id="username" /></td>
</tr>
<tr>
<td>Password</td>
<td><input type="password" name="password" id="password" /></td>
</tr>
<tr>
<td colspan="2" align="right"><input type="reset" value="Cancel" /><input type="button" value="Log in" onclick="login()" /></td>
</tr>
</table>
</form>
</div>
<div id="loggedin" style="display: none">
Currently logged in
<div id="loggedin_name"></div>
<input type="button" onclick="logout()" value="Log out" />
</div>
<input type="button" onclick="update()" value="Update" />
<span id="wsStatus"></span>
<div id="errormsg" style="display: none"></div>
</body>
</html>