
<?php

  $filesStorage='./flashprogrammer/filesStorage/';

  $file=getUrlParam('file');
  
  if (isset($file)){
    $file = $filesStorage.$file;
  
  
    if (!file_exists($file)){
      echo "missing file ".$file;
    
    } else {
    
      startProcess( './flashprogrammer/writeToFlash.sh \''.$file.'\'', getUrlParam('pageId'), 'filesStorage' );
    
    
    }


  } else {
    echo "failed to start flashing";
  }
?>
