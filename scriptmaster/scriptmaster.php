 

<?php

function expandArray( $array ){
 
  $output='';
  foreach ($array as $key => $value){
    $output.='&'.$key.'='.$value;
  }
  return $output;  
}

function startProcess( $cmd, $pageId, $action='', $opt = array(), $createLog = 1 ){
  
  $param = array('pageId'=> $pageId, 'action'=>$action);
  
  if (is_array($opt)){
    $param = array_merge( $param, $opt);  
  }
  
  $parameter=expandArray( $param );
  
  exec('./scriptmaster/startprocess.sh '.$cmd, $output, $retVal);  

  foreach ($output as $line){
    echo $line."<br>";
  }
  
  switch ($retVal){
    case 0: //echo 'starting script ...<br>';
	    break;
    case 1: echo "script allready exececuting ...<br>";break;
    case 127: echo "cannot run script<br>";break;
    default:
	echo "return ".$retVal."<br>";
  }

  
  
echo '<script type="text/javascript">

//var myVar=setInterval(function(){ajax_call()},1000);
window.onload=function(){
  loaded()
};
var updateTarget="log";
var xmlHttp = null;
try {
    // Mozilla, Opera, Safari sowie Internet Explorer (ab v7)
    xmlHttp = new XMLHttpRequest();
} catch(e) {
    try {
        // MS Internet Explorer (ab v6)
        xmlHttp  = new ActiveXObject("Microsoft.XMLHTTP");
    } catch(e) {
        try {
            // MS Internet Explorer (ab v5)
            xmlHttp  = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            xmlHttp  = null;
        }
    }
}

function loaded(){

  document.getElementById(updateTarget).innerHTML="document loaded";
  ajax_call( updateTarget );

}

function ajax_call() {
  xmlHttp.open("GET", "./scriptmaster/updateLog.php?app='.getUrlParam("app").$parameter.'", true);
  xmlHttp.onreadystatechange=function() { onAjaxCallDone();  };
  xmlHttp.send(null);
  
  setTimeout("ajax_call()", 500);
 
  return false;
}

function onAjaxCallDone(){
    if (xmlHttp.readyState==4) {
      if (xmlHttp.status == 200 || xmlHttp.status == 400){
	document.getElementById(updateTarget).innerHTML=xmlHttp.responseText;
      } else {
	document.getElementById("log").innerHTML="ajax error";
      }
    }
}

</script>';

/*
'<script type="text/javascript">
var xmlhttp=false;

//var myVar=setInterval(function(){ajax_call()},1000);
window.onload=function(){loaded()};

if (!xmlhttp && typeof XMLHttpRequest!="undefined") {
  xmlhttp = new XMLHttpRequest();
}
function loaded(){

  document.getElementById("log").innerHTML="document loaded";
  ajax_call();

}

function ajax_call() {
	xmlhttp.open("GET", "./scriptmaster/updateLog.php?app='.getUrlParam("app").$parameter.'", true);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			document.getElementById("log").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.send(null);
	var myVar=setTimeout(function(){ajax_call()},1000);

	return false;
}
</script>';
*/
//echo 'params '.$parameter.'<br>';
  
  if ($createLog == '1'){
    echo '<div id="log">init</div>';
  }

  
  
}

?>
