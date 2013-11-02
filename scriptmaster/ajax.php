
<script type="text/javascript">
var xmlhttp=false;

//var myVar=setInterval(function(){ajax_call()},70);
window.onload=function(){loaded()};

if (!xmlhttp && typeof XMLHttpRequest!="undefined") {
  xmlhttp = new XMLHttpRequest();
}
function loaded(){

  document.getElementById("log").innerHTML="document loaded";
  ajax_call();

}

function ajax_call() {
	xmlhttp.open("GET", "./scriptmaster/updateLog.php?page=<?php echo $GLOBALS["page"]; ?>&action=<?php echo $GLOBALS["action"]; ?>", true);
	xmlhttp.onreadystatechange=function() {
		if (xmlhttp.readyState==4) {
			//document.getElementById('xxx').value = xmlhttp.responseText;
			document.getElementById("log").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.send(null);
	var myVar=setTimeout(function(){ajax_call()},1000);

	return false;
}
</script>


<div id="log"></div>

 
