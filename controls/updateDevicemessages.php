
<?php

$output = shell_exec('sudo rm /tmp/dmesg.log');
$output = shell_exec('sudo dmesg > /tmp/dmesg.log');
$output = shell_exec('tail -n 20 /tmp/dmesg.log');

$output = preg_replace('/(?:\r\n?|\n)/', '<br>', $output);  
echo $output;


?>
