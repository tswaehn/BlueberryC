<?php


  $text=getUrlParam('text');

  echo "<h3>controls</h3>";
  
  echo '<script src="'.PLUGIN_DIR.'/js_stick.js"></script>';
  echo '<img id="Xstick" src="http://192.168.7.212:8080/?action=stream" />';
  echo '<div id="stick" style="float:right"></div>';
  

  function addTask(  $plugin, $action, $text, $obj=array()  ){
  
    $obj[] = array( $plugin => array( $action => $text ) );
    
    return $obj;
  }
  
  function encodeRequest( $obj ){
    
    $request = json_encode( $obj );
  
    return $request;
  }

  
  // send only if there is data 
  if ($text != ""){
  
    $fp = @fsockopen( 'localhost', 9000, $errno, $errstr, 10 );
    
    if (!$fp){
      echo "error no socket <br>";
      echo $errno. " " .$errstr;

    } else {

      stream_set_timeout($fp, 5 );
      $obj=addTask('rs232', 'tx', $text );
      fwrite( $fp, encodeRequest( $obj )  );
    }
    
    fclose( $fp );

  }  
  
 

?>

 
