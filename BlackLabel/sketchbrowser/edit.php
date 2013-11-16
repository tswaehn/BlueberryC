
<?php

    $sketchFolder = './sketches/';

   function lookForInoFile( $path ){
  
    $files = glob( $path.'/*.ino' );

    if (!empty($files)){
      // take always the first entry if multiple files exist
      return $files[0];
    }

    return null;
  }
  

  function loadFromFile($file){
    
    $text='';
    if (isset($file)){
      $text=file_get_contents('./sketches/blinky/Blink.ino');
    }
    
    return $text;
  }
  
  $sketch=getUrlParam('sketch');
  $dir= $sketchFolder.$sketch;

  $file=lookForInoFile( $dir );

  
  $text = loadFromFile($file);
  
?>

<h3>Edit Sketch - <?php echo $sketch; ?></h3>

<form action="<?php postToMe('compile') ?>" method="post">

<textarea name="text" cols="80" rows="30">

<?php echo $text; ?>

</textarea>
<p>
<input type="submit" name="submit" value="Cancel">
<input type="submit" name="submit" value="Save">
<input type="submit" name="submit" value="Compile and Run ...">
</form>

 
