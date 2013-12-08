
<?php

  $file='/tmp/download.hex';
  
  startProcess( './flashprogrammer/readFromFlash.sh \''.$file.'\'', getUrlParam('pageId'), 'read_name' );
  

?>

