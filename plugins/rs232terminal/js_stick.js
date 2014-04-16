

var div;
var marble;
var coord;
var xcenter=100;
var ycenter=100;
var stick_width=200;
var stick_height=200;
var xpos_last=0;
var ypos_last=0;

var send_complete=1;

var xmlHttp= null;

function mouseDrag(evt){
  xoffs=div.offsetLeft;
  yoffs=div.offsetTop;
  
  x=(evt.clientX-xoffs-xcenter);
  y=(evt.clientY-yoffs-ycenter);
  //invert y-position
  y=-1* y;

  
  if (x > 50){
    x=50;
  }
  if (x < -50){
    x=-50;
  }
  if (y > 50){
    y=50;
  }
  if (y < -50){
    y=-50;
  }
  
  sendPosition(x,y);
  
  coord.innerHTML='X='+x+' Y='+ y;
  setMarblePos(x,y);
}

function mouseStopped(evt){

  sendPosition(0,0);

  setMarblePos(0,0);
  coord.innerHTML='move stick';
}

function setMarblePos( x, y){
  xoffset=-25;
  yoffset=-25;
  
  y=-1*y;
 
  marble.style.top= ycenter+yoffset+y;
  marble.style.left= xcenter+xoffset+x;
  
}

function setup_stick(){
  div=document.getElementById('stick');
  
  // init
  div.innerHTML="loading stick"; 
  
  // style
  div.style.color="white";
  div.style.width=stick_width;
  div.style.height=stick_height;
  
  div.style.background="url(./plugins/rs232terminal/stick.png)";
  
  div.innerHTML='<div id="marble"><img src="./plugins/rs232terminal/marble.png"></div>';
  div.innerHTML = div.innerHTML  + '<div id="coord"></div>';
  
  //div.onmousemove = mouseMove;
  div.ondragover= mouseDrag;
  div.ondragend=mouseStopped;
  div.onclick=mouseDrag;
  //div.dragable=1;
  
  marble=document.getElementById('marble');
  marble.style.position="relative"; 
  setMarblePos(0,0);
  
  coord=document.getElementById('coord');
  coord.innerHTML='move stick';
  coord.style.position="relative"; 
  coord.style.top=10;
  coord.style.left=10;
  
  
}

function sendPosition(xpos,ypos){
/*
  if (abs(xpos - xpos_last) < 10){
    return;
  }
  if (abs(ypos - ypos_last) < 10){
    return;
  }
*/
  if (send_complete==0){
    return;
  }
  
  xpos_last=x;
  ypos_last=y;
    
  try {
      // Mozilla, Opera, Safari sowie Internet Explorer (ab v7)
      xmlHttp = new XMLHttpRequest();
  } catch(e) {
      try {
	  // MS Internet Explorer (ab v6)
	  xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch(e) {
	  try {
	      // MS Internet Explorer (ab v5)
	      xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
	  } catch(e) {
	      xmlHttp = null;
	  }
      }
  }
  
  xmlHttp.open("GET", "./plugins/rs232terminal/controls-xy.php?xpos="+xpos+"&ypos="+ypos, true);
  xmlHttp.onreadystatechange=function() { onAjaxCallDone();  };
  xmlHttp.send(null);
  send_complete=0;
  
}

function onAjaxCallDone(){
      
    if (xmlHttp.readyState==4) {
      if (xmlHttp.status == 200 || xmlHttp.status == 400){
	document.getElementById('coord').innerHTML=xmlHttp.responseText;
	send_complete=1;

      } else {
	// ... do nothing
      }
    }
}


window.onload=function(){
	  setup_stick();
};
