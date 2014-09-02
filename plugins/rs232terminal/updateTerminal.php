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
  $lines= getUrlParam( "lines" );
  if (empty($lines)){
    $lines= 100;
  }
  

  $serverTalk= new ServerTalk();

  $serverTalk->addTask('rs232', $action, $lines);

  $response = $serverTalk->transferSendObjectToSocket();
  
  echo $response;
      

?>
