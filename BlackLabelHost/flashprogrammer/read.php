
<?php

$file = '/tmp/download.hex';

echo 'starting script ...<br>';

$output= exec('./flashprogrammer/readFromFlash.sh \''.$file.'\'');  
echo $output;

?>

<script type="text/javascript">
var xmlhttp=false;

var myVar=setInterval(function(){ajax_call()},1000);

if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
  xmlhttp = new XMLHttpRequest();
}

function ajax_call() {
	xmlhttp.open("GET", './flashprogrammer/updateLog.php', true);
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
<form action="?page=prog&flashstate=read_name" method="post">

<input type="submit" name="submit" value="Done">
</form>
