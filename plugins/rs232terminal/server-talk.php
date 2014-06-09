<?php


  function addTask(  $plugin, $action, $text, $obj=array()  ){
  
    $obj[] = array( $plugin => array( $action => $text ) );
    
    return $obj;
  }
  
  function encodeRequest( $obj ){
    
    $request = json_encode( $obj );
  
    return $request;
  }

  function transferDataToSocket( $fp, $data ){
  
    $data = "<CONTENT>".$data."</CONTENT>";

    // send complete message
    fwrite( $fp, $data  );

    // now wait for the result/response
    $response = "";
    do {
      
      $info = stream_get_meta_data($fp);

      $package = fread( $fp, 10 );
      $response .= $package;
      
      $msg_start=strpos( $response, "<RESPONSE>" );
      $msg_end=strpos( $response, "</RESPONSE>" );

    } while ((!$info['timed_out']) && ($msg_end === false));
    
    if ($msg_end === false){
      echo "failed to send data";
    } else {
      // strip content tags
      $response = preg_replace("/<RESPONSE>|<\/RESPONSE>/", "", $response );
      //echo $response;
    }
    
    return $response;
  }
  
  
  ?>
