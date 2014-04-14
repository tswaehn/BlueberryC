<?php

  include( PLUGIN_DIR.'js_update.php' );

  $text=getUrlParam('text');

  echo "<h3>terminal</h3>";
  
  echo '<pre id="log">';
  echo 'empty';
  echo '</pre>';
  
  echo '<form action="'.linkToMe('send').'" method="post">';
  echo '<input type="edit" name="text" value="'.$text.'">';
  echo '<input type="submit" value="send">';
  echo '</form>';
  
  insertUpdateScript( PLUGIN_DIR.'updateTerminal.php', 'log');  
  

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

