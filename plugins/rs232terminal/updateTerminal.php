<?php

  extract( $_GET, EXTR_PREFIX_ALL, "url" );

  include( "server-talk.php" );
  
  
  function getGlobal( $var ){
    
    if (!isset($GLOBALS[$var])){
      $ret='';
    } else {
      $ret=$GLOBALS[$var];
    }

    return $ret;
  }
  
  function getUrlParam( $var ){
  
    $urlParam = 'url_'.$var;
    
    $ret = getGlobal( $urlParam );
    return $ret;
  }

  $action=getUrlParam("action");
  if ($action==""){
    $action="update";
  }

 
  $fp = @fsockopen( 'localhost', 9000, $errno, $errstr, 10 );
  
  if (!$fp){
    echo "error no socket <br>";
    echo $errno. " " .$errstr;

  } else {
    
    $obj=addTask('rs232', $action, '');

    $data = transferDataToSocket( $fp, encodeRequest( $obj )  );
    
    echo $data;
      

  }
  
  fclose( $fp );

?>
