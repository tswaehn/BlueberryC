
<?php

echo '<h3>compiling ...</h3>';
  if (!isset($url_text)){
    //$text='';
  } else {
    $text=$url_text;
    file_put_contents('./sketcheditor/sketches/Blink.ino', $text );
  }

  start( './sketcheditor/execute_make.sh', 'sketch', '');

?>
