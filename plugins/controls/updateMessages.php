
<?php

$output = shell_exec('sudo tail -n 20 /var/log/messages');

$output = preg_replace('/(?:\r\n?|\n)/', '<br>', $output);  
echo $output;


?>
