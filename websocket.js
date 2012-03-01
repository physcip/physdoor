var s, wsStatusEl
var wTimeout
	
function wsConnect(url)
{
	wsStatusEl = document.getElementById('wsStatus')
	wsStatusEl.innerText = 'connecting...';
	
	clearTimeout(wTimeout);

	/* http://dev.w3.org/html5/websockets/ */ 

	s = new WebSocket(url); 

	s.onopen = function() {
		wsStatusEl.innerText = 'connected!';
	}

	s.onclose = function() {
		wsStatusEl.innerText = 'connection closed';
		wTimeout = window.setTimeout("wsConnect('" + url + "')", 2000);
	}

	s.onerror = function(e) {
		wsStatusEl.innerText = 'error';
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