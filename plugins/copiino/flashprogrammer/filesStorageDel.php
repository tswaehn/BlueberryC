<?php

$filesStorage= PLUGIN_DIR.'flashprogrammer/filesStorage/';
$trash = PLUGIN_DIR.'flashprogrammer/trash/';

$url_file=getUrlParam('file');

if (isset($url_file)){
  $file = $url_file;
  
  if (!file_exists($filesStorage.$file)){
    echo "missing file ".$filesStorage.$file;
  } else {
    if (rename( $filesStorage.$file, $trash.$file )){
      echo 'moved '.$file. ' into trash.';
    } else {
      echo 'failed to move '.$file.' into trash.';
    }
  }
  
} else {
  
  echo "failed to delete file";
  
}

?>

<form action="<?php postToMe('filesStorage'); ?>" method="post">
<input type="submit" name="submit" value="Done">
</form>
