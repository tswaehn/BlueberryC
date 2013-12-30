<?php

echo 'stopping AVR ...<br>';

$output= shell_exec('sudo '.PLUGIN_DIR.'avrcontrols/stopAVR.sh');  
echo $output;

?>


<p>
<form action="<?php postToMe(); ?>" method="post">

<input type="submit" name="submit" value="Done">
</form>
