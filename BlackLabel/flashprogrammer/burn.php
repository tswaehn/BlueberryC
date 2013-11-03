
<?php

  $filesStorage='./flashprogrammer/filesStorage/';

  if (isset($url_file)){
    $file = $filesStorage.$url_file;
  
  
    if (!file_exists($file)){
      echo "missing file ".$file;
    
    } else {
    
      start( './flashprogrammer/writeToFlash.sh \''.$file.'\'', 'prog', 'filesStorage' );
    
    
    }


  } else {
    echo "failed to start flashing";
  }
?>
