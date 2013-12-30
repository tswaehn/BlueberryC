
<?php

  echo '<h3>compiling ...</h3>';

  $text = getUrlParam('text');
  if ($text != ''){
    // save
    file_put_contents( PLUGIN_DIR.'sketches/blinky/Blink.ino', $text );
  }

  start( PLUGIN_DIR.'sketcheditor/execute_make.sh', 'sketch', '');

?>
 
