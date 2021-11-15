var pop = null;

function popdown() {
  if (pop && !pop.closed) pop.close();
}

function popup(obj,w,h) {
  var url = (obj.getAttribute) ? obj.getAttribute('href') : obj.href;
  if (!url) return true;
  var args = "fullscreen";
  setTimeout(function() {
    pop.close();
    }, 120000);
  popdown();
  pop = window.open(url,'',args);
  if (pop.outerWidth < screen.availWidth || pop.outerHeight < screen.availHeight)
  {
     pop.moveTo(0,0);
     pop.resizeTo(screen.availWidth, screen.availHeight);
  }
  return (pop) ? false : true;
}

window.onunload = popdown;
window.onfocus = popdown;

