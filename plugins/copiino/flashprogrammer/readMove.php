<?php

$filesStorage= PLUGIN_DIR.'flashprogrammer/filesStorage/';
$download = '/tmp/download.hex';

$file=getUrlParam('file');
if ($file!=''){
  
  if (!file_exists($download)){
    echo "missing file ".$download;
  } else {
    if (copy( $download, $filesStorage.$file )){
      echo 'moved '.$file. ' into filesStorage.';
    } else {
      echo 'failed to move '.$file.' into filesStorage.';
    }
  }
  
} else {
  
  echo "failed to copy file";
 
  echo "<p>";
  print_r($_GET);
  echo "<p>";
  print_r($_POST);

}

?>

<form action="<?php postToMe('filesStorage'); ?>" method="post">
<input type="submit" name="submit" value="Done">
</form>
