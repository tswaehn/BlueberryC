<div id="title">
  <div id="version">
  <?php
    $file='./build_nr';
    
    // check if file is existing
    if ((isset($file)) && (is_file($file))){
      $version=file_get_contents( $file );
      //$date='('.date ("M d Y H:i:s", filemtime($file)).')';
      $date='('.date ("M d Y", filemtime($file)).')';
    } else {
      echo 'failed to load '.$file.'<br>';
    }  
    echo "BlueberryControl<br>".$version.' '.$date;
   ?>
  </div>

  <h1><?php echo '..::'.$APP['caption'].'::..'; ?></h1>
</div>