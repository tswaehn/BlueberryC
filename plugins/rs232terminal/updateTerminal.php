<?php

  extract( $_GET, EXTR_PREFIX_ALL, "url" );

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

  function addTask(  $plugin, $action, $text, $obj=array()  ){
  
    $obj[] = array( $plugin => array( $action => $text ) );
    
    return $obj;
  }
  
  function encodeRequest( $obj ){
    
    $request = json_encode( $obj );
  
    return $request;
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
  

      stream_set_timeout($fp, 5 );
    
      $obj=addTask('rs232', $action, '');
      
      fwrite( $fp, encodeRequest( $obj )  );


      $info = stream_get_meta_data($fp);

      while ((!feof($fp)) && (!$info['timed_out'])) {
	$info = stream_get_meta_data($fp);

	$disp = fread( $fp, 1024 );

	if ($disp != false){
	  echo $disp;
	}
      }
      fclose( $fp );

  }


?>
