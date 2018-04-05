var PHYSDASH_SOURCE = "https://www.physcip.uni-stuttgart.de/physdash/?kioskmode=1";

// UPDATE_STATUS_INTERVAL: Interval (in seconds) in which logged in / logged out status of primary is updated
// Primary updates also take care of locking / unlocking computer screens
var UPDATE_STATUS_INTERVAL = 60;

// After a user has locked out, wait this time (in seconds) before locking all screens
var SCREENLOCK_GRACEPERIOD = 40;

var lockscreen_gracetimer;

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
 * Screen locking
 */
function lockScreens() {
	physdoorAction("lock", {}, function(res) {}, function() {});
}

function unlockScreens() {
	physdoorAction("unlock", {}, function(res) {}, function() {});
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
	// Only clear username and password when login section was previously hidden
	if (document.getElementById("login_section").style.display != "flex") {
		document.getElementById("password").value = "";
		document.getElementById("username").value = "";
	}

	document.getElementById("loggedin_section").style.display = "none";
	document.getElementById("login_section").style.display = "flex";
}

function showLoggedinSection(name) {
	document.getElementById("loggedin_name").textContent = name.split(" ")[0];
	document.getElementById("loggedin_section").style.display = "flex";
	document.getElementById("login_section").style.display = "none";
}

function updateStatus() {
	physdoorAction("status", {}, function(res) {
		if ("loggedin" in res && res.loggedin === true) {
			showLoggedinSection(res.name);
			unlockScreens();
			clearTimeout(lockscreen_gracetimer);
		} else {
			if ("loggedin" in res && res.loggedin === false)
				lockscreen_gracetimer = setTimeout(lockScreens, SCREENLOCK_GRACEPERIOD * 1000);
			showLoginSection();
		}
	}, showLoginSection);
}

/*
 * Login
 */
function onLoginClick() {
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	document.getElementById("password").value = "";

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
		if ("loggedin" in res && res.loggedin === true)
			updateStatus();
		else
			showErrorMessage(res.error);
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

	// Update status every UPDATE_STATUS_INTERVAL seconds in case we are out of sync for some reason
	// This also takes care of locking / unlocking screens of previously disconnected clients
	setInterval(updateStatus, UPDATE_STATUS_INTERVAL * 1000);
});
