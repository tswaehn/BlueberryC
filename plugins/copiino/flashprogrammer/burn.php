
<?php

  $filesStorage= PLUGIN_DIR.'flashprogrammer/filesStorage/';

  $file=getUrlParam('file');
  
  if (isset($file)){
    $file = $filesStorage.$file;
  
  
    if (!file_exists($file)){
      echo "missing file ".$file;
    
    } else {
    
      startProcess( PLUGIN_DIR.'flashprogrammer/writeToFlash.sh \''.$file.'\'', getUrlParam('pageId'), 'filesStorage' );
    
    
    }


  } else {
    echo "failed to start flashing";
  }
?>
