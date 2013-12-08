
<?php

  echo '<h3>compiling ...</h3>';

  $text = getUrlParam('text');
  if ($text != ''){
    // save
    file_put_contents('./sketches/blinky/Blink.ino', $text );
  }

  start( './sketcheditor/execute_make.sh', 'sketch', '');

?>
 
