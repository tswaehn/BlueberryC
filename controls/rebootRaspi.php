
<?php

echo 'rebooting ...<br>';

$output= exec('./controls/rebootRaspi.sh' );  
echo $output;

?>

<script type="text/javascript">
var xmlhttp=false;

var myVar=setInterval(function(){ajax_call()},2000);

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
  xmlhttp = new XMLHttpRequest();
}

function ajax_call() {
	xmlhttp.open("GET", './controls/updateMessages.php', true);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			//document.getElementById('xxx').value = xmlhttp.responseText;
			document.getElementById("log").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.send(null)
	return false;
}
</script>


<div id="log"></div>

