<?php

$trash = './flashprogrammer/trash/';

echo "<h3>Trash</h3>";

function renderFile( $filename ){
  
  echo "<tr>";
  echo '<td>'.$filename.'</td>';
  echo '<td><a href="?page=prog&flashstate=trash_restore&file='.$filename.'">restore</a></td>';  
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


displayFiles($trash);
    
?>

