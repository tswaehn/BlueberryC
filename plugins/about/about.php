 
<h3>about</h3>

<?php


    $text='';
    $date='';
    $file='./build_nr';
    
    // check if file is existing
    if ((isset($file)) && (is_file($file))){
      $text=file_get_contents( $file );
      $date='('.date ("F d Y H:i:s.", filemtime($file)).')';
    } else {
      echo 'failed to load '.$file.'<br>';
    }
    
    
    echo "BlueberryC ".$text." ".$date;

    include( PLUGIN_DIR.'xUpgrades.php' );
    
    echo '<p>';

    $upgrade = new Upgrade();
    $upgrade->request();

?> 
