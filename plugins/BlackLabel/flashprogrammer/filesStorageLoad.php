<?php
if ($_FILES["file"]["error"] > 0)
  {
  echo "Error: " . $_FILES["file"]["error"] . "<br>";
  }
else
  {
  echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  echo "Type: " . $_FILES["file"]["type"] . "<br>";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
  echo "Stored in: " . $_FILES["file"]["tmp_name"];

  echo '<p>';
  
  $source = $_FILES["file"]["tmp_name"];
  $destination = './flashprogrammer/filesStorage/'.$_FILES["file"]["name"];
  
 if ( move_uploaded_file( $source, $destination) == 1){
    echo 'moved file to '.$destination;
  } else {
    echo 'failed to move file to '.$destination;
  }

}
  
  
?> 


<form action="<?php postToMe('filesStorage'); ?>" method="post">
<input type="submit" name="submit" value="Done">
</form>