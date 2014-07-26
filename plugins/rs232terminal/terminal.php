<?php

  include( PLUGIN_DIR.'server-talk.php' );
  
  $sendToServer=getUrlParam('sendToServer');

  echo "<h3>terminal</h3>";
  
  echo '<pre id="log">';
  echo 'empty';
  echo '</pre>';
  
  echo '<form action="'.linkToMe('send').'" method="post">';
  echo '<input type="edit" name="sendToServer" value="'.$sendToServer.'">';
  echo '<input type="submit" value="send">';
  echo '</form>';
  
  insertUpdateScript( PLUGIN_DIR.'updateTerminal.php', 'log');  
  
  
  // send only if there is data 
  if ($sendToServer != ""){
  
    $fp = @fsockopen( 'localhost', 9000, $errno, $errstr, 10 );
    
    if (!$fp){
      echo "error no socket <br>";
      echo $errno. " " .$errstr;

    } else {

      $obj=addTask('rs232', 'tx', $sendToServer );
      transferDataToSocket( $fp, encodeRequest( $obj )  );
    }
    
    fclose( $fp );

  }  
  
  
  // display logfiles
  $logFolder = "/usr/share/BlueberryC/plugins/rs232/logs/";
  if (file_exists( $logFolder )){
    $files = scandir( $logFolder, 1 );
    if (!empty($files)){
      
      echo "logfiles for download";
      echo "<ul>";
      foreach( $files as $file ){
        if( ($file==".") || ($file=="..") ){
          continue;
        }
        echo '<li><a href="./lib/downloadFile.php?file='.$logFolder.$file.'">'.$file."</a></li>";      
      }
      
      echo "</ul>";
      
    
    }
    }
?>

