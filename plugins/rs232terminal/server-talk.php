<?php

  //define("BLUEBERRY_SERVER", "192.168.5.130" );
  define("BLUEBERRY_SERVER", "localhost" );
  
  define("SERVER_TALK_ERROR", "error no socket" );

class ServerTalk {

  private $fp;
  private $targetUrl;
  private $sendObject;
  
  // 
  function __construct( $targetUrl= BLUEBERRY_SERVER){

    $this->targetUrl= $targetUrl;
    $this->sendObject= array();

    $this->fp = @fsockopen( $this->targetUrl, 9000, $errno, $errstr, 10 );
    
    if (!$this->fp){
      echo SERVER_TALK_ERROR."<br>";
      echo $errno. " " .$errstr;
      return;
    }
  
    echo "socket connection created\n";
    
    
  }
  
  function __destruct(){

    if (!$this->fp){
      echo SERVER_TALK_ERROR."<br>";
      return;
    }
  
    
    fclose( $this->fp );
    echo "socket connection destroyed\n";
      
  }
  

  function addTask(  $plugin, $action, $text  ){
    
    $this->sendObject[]= array( $plugin => array( $action => $text ) );

  }
  
  function encodeRequest(){
    
    $request = json_encode( $this->sendObject );
  
    return $request;
  }

  function transferSendObjectToSocket(){

  
    // check for open socket
    if (!$this->fp){
      return SERVER_TALK_ERROR;
    }
  
    // convert sendObject to String
    $jsonString= $this->encodeRequest();
    
    // add start/end- tags
    $jsonString = "<CONTENT>".$jsonString."</CONTENT>";

    // send complete message    
    fwrite( $this->fp, $jsonString  );

    // now wait for the result/response
    $response = "";
    do {
      
      $info = stream_get_meta_data($this->fp);

      $package = fread( $this->fp, 1000 );
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
  
}

?>
