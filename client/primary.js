var PHYSDASH_SOURCE = "https://www.physcip.uni-stuttgart.de/physdash/?kioskmode=1";

/*
 * Error messages
 */
function showErrorMessage(text) {
	document.getElementById("error_dialog_message").textContent = text;
	document.getElementById("error_message").style.display = "flex";
}

function onErrorClose() {
	document.getElementById("error_message").style.display = "none";
}

function timeoutError() {
	showErrorMessage("A timeout occurred - please contact the administrators if this keeps happening.");
}

/*
 * Physdash frame
 */
function onPhysdashClose() {
	document.getElementById("physdash_container").style.display = "none";
}

function onPhysdashOpen() {
	document.getElementById("physdash_frame").src = PHYSDASH_SOURCE;
	document.getElementById("physdash_container").style.display = "flex";
}

/*
 * Update status: Hide / show login form / currently logged in-section
 * In case of failure: Default to displaying login section and hiding loggedin section
 */
function showLoginSection() {
	document.getElementById("password").value = "";
	document.getElementById("username").value = "";
	document.getElementById("loggedin_section").style.display = "none";
	document.getElementById("login_section").style.display = "flex";
}

function showLoggedinSection(name) {
	document.getElementById("loggedin_name").textContent = name;
	document.getElementById("loggedin_section").style.display = "flex";
	document.getElementById("login_section").style.display = "none";
}

function updateStatus() {
	physdoorAction("status", {}, function(res) {
		if ("loggedin" in res && res.loggedin === true)
			showLoggedinSection(res.name);
		else
			showLoginSection();
	}, showLoginSection);
}

/*
 * Login
 */
function onLoginClick() {
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;

	if (username.length == 0) {
		showErrorMessage("Please enter a valid username");
		return;
	}

	if (password.length == 0) {
		showErrorMessage("Please enter a valid password");
		return;
	}

	physdoorAction("login", {
		user : username,
		password : password
	}, function(res) {
		if ("loggedin" in res && res.loggedin === true) {
			updateStatus();
		} else {
			document.getElementById("password").value = "";
			showErrorMessage(res.error);
		}
	}, timeoutError);
}

/*
 * Logout
 */
function onLogoutClick() {
	physdoorAction("logout", {}, function(res) {
		if ("loggedin" in res && res.loggedin === false) {
			updateStatus();
		} else {
			showErrorMessage(res.error);
		}
	}, timeoutError);
}

document.addEventListener("DOMContentLoaded", function() {
	document.getElementById("physdash_close_button").addEventListener("click", onPhysdashClose);
	document.getElementById("physdash_open_button").addEventListener("click", onPhysdashOpen);
	document.getElementById("error_dialog_ok").addEventListener("click", onErrorClose);
	document.getElementById("login_button").addEventListener("click", onLoginClick);
	document.getElementById("logout_button").addEventListener("click", onLogoutClick);
	updateStatus();
});
