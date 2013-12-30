
<?php

  $file='/tmp/download.hex';
  
  startProcess( PLUGIN_DIR.'flashprogrammer/readFromFlash.sh \''.$file.'\'', getUrlParam('pageId'), 'read_name' );
  

?>

