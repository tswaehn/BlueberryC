<?php

$filesStorage='./flashprogrammer/filesStorage/';
$trash = './flashprogrammer/trash/';

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

<form action="?page=prog&action=filesStorage" method="post">
<input type="submit" name="submit" value="Done">
</form>
