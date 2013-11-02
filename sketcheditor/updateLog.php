
<?php

$output = shell_exec('cat /tmp/log.txt');

$output = preg_replace('/(?:\r\n?|\n)/', '<br>', $output);  
echo $output;
  
?>
 
