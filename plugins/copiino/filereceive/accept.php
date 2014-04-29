<?php

  include('serverconfig.php');
  include('diverse.php');
  include('xSketchServerSync.php');

  date_default_timezone_set('Europe/London');
  
  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );
  
    
  $sync = new SketchServerSync();
  
  $ret=$sync->check();
  if ($ret){
    echo $ret.'<br>';
    die();
  } else {
    $errorCode= $sync->getFiles();
    if ( $errorCode == 0){
      echo 'ok<br>';
    } else {
      echo 'err '.$errorCode.' upload failed <br>';
    }
  }

?>



