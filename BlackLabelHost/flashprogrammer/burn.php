
<?php

$filesStorage='./flashprogrammer/filesStorage/';

if (isset($url_file)){
  $file = $filesStorage.$url_file;
  
  
  if (!file_exists($file)){
    echo "missing file ".$file;
   
  } else {

    echo 'starting script ...<br>';

    $output= exec('./flashprogrammer/writeToFlash.sh \''.$file.'\'');  
    echo $output;
  
  }


} else {
  echo "failed to start flashing";
}
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
<form action="?page=prog" method="post">

<input type="submit" name="submit" value="Done">
</form>
