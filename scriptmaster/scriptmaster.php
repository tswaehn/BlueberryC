 

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
//echo 'params '.$parameter.'<br>';
  
  if ($createLog == '1'){
    echo '<div id="log">init</div>';
  }

  
  
}

?>
