
<?php

$output = shell_exec('sudo tail -n 10 /var/log/messages');
//$output .= shell_exec('ls -la');

$output = preg_replace('/(?:\r\n?|\n)/', '<br>', $output);  
echo $output;

echo "done";

?>
