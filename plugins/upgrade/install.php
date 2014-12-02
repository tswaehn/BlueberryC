<h3>install</h3>
 
<?php

  $ver=getUrlParam('ver');
  $file=getUrlParam('file');
  $link=getUrlParam('link');

  echo 'installing '.$file.' ('.$ver.')<br>';
  echo $link.'<br>';

  
    
  startProcess( PLUGIN_DIR.'upgrade.sh \''.$link.'\' \''.$file.'\'', getUrlParam('pageId'), '' );
    

?>