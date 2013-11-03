 

<?php

function start( $cmd, $page_, $action_ ){
  
  $GLOBALS["page"]=$page_;
  $GLOBALS["action"]=$action_;
  
  exec('../scriptmaster/startprocess.sh '.$cmd, $output, $retVal);  

  foreach ($output as $line){
    echo $line."<br>";
  }
  
  switch ($retVal){
    case 0: echo 'starting script ...<br>';break;
    case 1: echo "script allready exececuting ...<br>";break;
    case 127: echo "cannot run script<br>";break;
    default:
	echo "return ".$retVal."<br>";
  }

  
  
echo '<script type="text/javascript">
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
	xmlhttp.open("GET", "./scriptmaster/updateLog.php?page='.$GLOBALS["page"].'&action='.$GLOBALS["action"].'", true);
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

echo '<div id="log">init</div>';

  
  
}

?>
