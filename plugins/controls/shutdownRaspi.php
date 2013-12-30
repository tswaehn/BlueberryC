
<?php

echo '<h3>shutdown ...</h3>';

$output= exec( PLUGIN_DIR.'shutdownRaspi.sh' );  
echo $output;

?>

<script type="text/javascript">
var xmlhttp=false;

var myVar=setInterval(function(){ajax_call()},2000);

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
  xmlhttp = new XMLHttpRequest();
}

function ajax_call() {
	xmlhttp.open("GET", './plugins/controls/updateMessages.php', true);
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

<p>
Raspi is stopping all services and will switch of processing. Please wait for at least 40sec.
You then may disconnect the power. 
<p>
Please note: As all services beeing stopped this page wont be processed and thus reloading this page in the browser disconnects the browser.
