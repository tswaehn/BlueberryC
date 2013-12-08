<?php

$filesStorage='./flashprogrammer/filesStorage/';
$trash = './flashprogrammer/trash/';

  $url_file=getUrlParam('file');
  
if (isset($url_file)){
  $file = $url_file;
  
  if (!file_exists($trash.$file)){
    echo "missing file ".$trash.$file;
  } else {
    if (rename( $trash.$file, $filesStorage.$file )){
      echo 'moved '.$file. ' into filesStorage.';
    } else {
      echo 'failed to move '.$file.' into filesStorage.';
    }
  }
  
} else {
  
  echo "failed to restore file";
  
}

?>

<form action="<?php postToMe('filesStorage'); ?>" method="post">
<input type="submit" name="submit" value="Done">
</form>
