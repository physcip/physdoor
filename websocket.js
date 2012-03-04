var s
//var wsStatusEl
var wTimeout

function wsError(msg)
{
	$('#loggedin').hide('slow');
	$('#login').hide('slow');
	display_error(msg);
}
	
function wsConnect(url)
{
	wsStatusEl = document.getElementById('wsStatus')
	//wsStatusEl.innerText = 'connecting...';
	
	clearTimeout(wTimeout);

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