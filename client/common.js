var UPDATE_ANNOUNCEMENTS_MINUTES = 5;

/*
 * Automatic announcement updates
 */
function addAnnouncement(markdown) {
	htmlContent = marked(markdown);
	if (htmlContent.length > 0) {
		document.getElementById("announcements").innerHTML = htmlContent;
		document.getElementById("announcement_section").style.display = "flex";
	}
}

function removeAnnouncement() {
	document.getElementById("announcement_section").style.display = "none";
}

function updateAnnouncements() {
	var req = new XMLHttpRequest();

	// Workaround: Use random token string to make sure browser doesn't cache announcement page
	var token = Math.random().toString(36).substring(7);

	req.open("GET", "announcements.md?token=" + token, true);
	removeAnnouncement();

	req.onload = function() {
		if (req.status == 200)
			addAnnouncement(req.responseText);
	};

	req.send();
}

/*
 * Physdoor server API access
 */
function physdoorAction(name, data, cb, cbTimeout) {
	// Encode data object as x-www-form-urlencoded
	var data_encoded = [];
	for (var key in data)
		data_encoded.push(encodeURIComponent(key) + "=" + encodeURIComponent(data[key]));

	// Send POST request to physreg API, call callback with response
	var req = new XMLHttpRequest();
	req.open("POST", "../server/server.php?action=" + name, true);
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
	req.timeout = 3000;

	req.onload = function() {
		if (req.status == 200)
			cb(JSON.parse(req.responseText));
	};

	req.ontimeout = function() {
		cbTimeout();
	};

	req.onreadystatechange = function() {
		if (req.readyState == 4 && req.status != 200)
			cbTimeout();
	};

	req.send(data_encoded.join("&"));
}

document.addEventListener("DOMContentLoaded", function() {
	updateAnnouncements();
	setInterval(updateAnnouncements, UPDATE_ANNOUNCEMENTS_MINUTES * 60 * 1000);
});
