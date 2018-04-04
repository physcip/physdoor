var LONGPOLLING_TIMEOUT = 3 * 60;

/*
 * Update status: Hide / show "nobody logged in" / "currently logged in"-section
 * In case of failure: Default to displaying "nobody logged in" section and hiding "currently logged in" section
 */
function showNobodyLoggedinSection() {
	document.getElementById("loggedin_section").style.display = "none";
	document.getElementById("noone_loggedin_section").style.display = "flex";
}

function showLoggedinSection(name) {
	document.getElementById("loggedin_name").textContent = name;
	document.getElementById("loggedin_section").style.display = "flex";
	document.getElementById("noone_loggedin_section").style.display = "none";
}

function updateStatus() {
	physdoorAction("status", {}, function(res) {
		if ("loggedin" in res && res.loggedin === true)
			showLoggedinSection(res.name);
		else
			showNobodyLoggedinSection();
	}, showNobodyLoggedinSection);
}

/*
 * Long polling - server answers long polling request when status has changed
 * This way, the secondary immediately notices status changes without causing excessive network / server load
 */
function longPoll() {
	var req = new XMLHttpRequest();
	req.open("GET", "../server/poll.php", true);
	req.timeout = LONGPOLLING_TIMEOUT * 1000;

	req.onload = function() {
		if (req.status == 200) {
			updateStatus();
			longPoll();
		}
	};

	req.send();
}

document.addEventListener("DOMContentLoaded", function() {
	updateStatus();
	longPoll();

	setInterval(longPoll, LONGPOLLING_TIMEOUT * 1000);
});
