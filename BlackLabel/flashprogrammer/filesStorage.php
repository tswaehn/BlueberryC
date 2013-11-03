
<?php

$fileStorage = './flashprogrammer/filesStorage/';

echo "<h3>myFilesStorage</h3>";

function renderFile( $filename ){
  
  echo "<tr>";
  echo '<td><a href="'.linkToMe('filesStorage_del').'&file='.$filename.'">del</a></td>';
  echo '<td>'.$filename.'</td>';
  echo '<td><a href="'.linkToMe('burn').'&file='.$filename.'">burn</a></td>';  
  echo "</tr>";

}


function displayFiles( $directory ){

  echo "Files:<br>";

  echo "<table>";
  if ($handle = opendir( $directory )) {

    while (false !== ($file = readdir($handle))) {
	if ($file != "." && $file != "..") {
	    renderFile($file);
	}    
    }
    closedir($handle);
  } else {
    echo "<tr><td>file listing failed</td></tr>";
  }
  echo "</table>";
}


displayFiles($fileStorage);
    
?>
<p>
You find all deleted files in <a href="<?php postToMe('trash'); ?>">trash</a>

<p>
Upload a new File:

<form action="<?php postToMe('filesStorage_load'); ?>" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>

