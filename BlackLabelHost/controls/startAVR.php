<?php

echo 'starting AVR ...<br>';

$output= exec('sudo ./controls/startAVR.sh');  
echo $output;

?>


<p>
<form action="?page=controls" method="post">

<input type="submit" name="submit" value="Done">
</form>
