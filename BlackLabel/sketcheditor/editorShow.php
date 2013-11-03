
<?php

  
?>
 
<form action="<?php postToMe('compile') ?>" method="post">

<?php

  function loadFromFile(){
    readfile('./sketcheditor/sketches/Blink.ino');
  }

  echo '<textarea name="text" cols="100" rows="30">';
  loadFromFile();
  echo '</textarea>';
?>

<p>
<input type="submit" name="submit" value="Compile">
</form>

