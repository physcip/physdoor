var s
//var wsStatusEl
var wTimeout

function wsError(msg)
{
	$('#loggedin').hide('slow');
	$('#login').hide('slow');
	display_error(msg);
}

function wsStatus()
{
	if (s.readyState == 0)
	{
		wsError("WebSocket connection timed out.<br /> \
Please make sure your browser supports RFC 6455.<br /> \
If you are using Firefox, you may need to set <i>network.websocket.allowInsecureFromHTTPS</i>.");
	}
}
	
function wsConnect(url)
{
	wsStatusEl = document.getElementById('wsStatus')
	//wsStatusEl.innerText = 'connecting...';
	
	clearTimeout(wTimeout);
	
	wTimeout = window.setTimeout("wsStatus()", 5000);

	/* http://dev.w3.org/html5/websockets/ */ 

	s = new WebSocket(url); 

	s.onopen = function() {
		//wsStatusEl.innerText = 'connected!';
		update();
	}

	s.onclose = function() {
		//wsStatusEl.innerText = 'connection closed';
		wsError('<b>WebSocket Error</b><br />The connection to ' + url + ' was closed.');
		wTimeout = window.setTimeout("wsConnect('" + url + "')", 2000);
	}

	s.onerror = function(e) {
		//wsStatusEl.innerText = 'error';
		wsError('<b>WebSocket Error</b><br />The connection to ' + url + ' failed with an error:' + e.data);
		console.log('error', e);
		wTimeout = window.setTimeout("wsConnect('" + url + "')", 2000);
	}

	s.onmessage = function(e) {
		update();
	}
}

function wsSend(msg)
{
	s.send(msg);
}
