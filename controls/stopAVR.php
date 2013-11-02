<?php

echo 'stopping AVR ...<br>';

$output= exec('sudo ./controls/stopAVR.sh');  
echo $output;

?>


<p>
<form action="?page=controls" method="post">

<input type="submit" name="submit" value="Done">
</form>
