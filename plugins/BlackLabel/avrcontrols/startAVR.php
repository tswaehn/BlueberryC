<?php

echo 'starting AVR ...<br>';

$output= shell_exec('sudo ./avrcontrols/startAVR.sh');  
echo $output;

?>


<p>
<form action="<?php postToMe(); ?>" method="post">

<input type="submit" name="submit" value="Done">
</form>
