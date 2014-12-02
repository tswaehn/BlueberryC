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
      echo $response;
    }
    
    return $response;
  }
  
  
  function sendNewPosition( $x, $y ){
  
  
    $fp = @fsockopen( 'localhost', 9000, $errno, $errstr, 5000 );
    
    if (!$fp){
      echo "error no socket <br>";
      echo $errno. " " .$errstr;
      return;
    } else {
      
      // invert x
      $x = -1*$x;
      
      // set direction fwd/rwd
      if ($y > 0){
	// fwd
	$dirLeft = chr(102).chr(0+ord('a')).chr(1+ord('a'));
	$dirRight = chr(103).chr(0+ord('a')).chr(0+ord('a'));
      } else {
	// rwd
	$dirLeft = chr(102).chr(0+ord('a')).chr(0+ord('a'));
	$dirRight = chr(103).chr(0+ord('a')).chr(1+ord('a'));
      }
      
      $max = 50;
      
      //$speed = sqrt( $x^2 + $y^2 );
      $speed = abs( $y );
      
      if ($speed > 50){
	$speed=50;
      }
      
      if ($speed < 10){
	$speed=0;
      }
      if (abs($x)<10){
	$x = 0;
      }
      
      if ($x > $max){
	$x=$max;
      }
      if ($x < (-1*$max)){
	$x = -1*$max;
      }
      
      
      
      $maxPWM = 255/$max * $speed;

      $wheelLeft  = floor( $maxPWM * abs( ($max+$x) / $max) / 2 );
      $wheelRight = floor( $maxPWM * abs( ($max-$x) / $max) / 2 );
      
      if ($wheelLeft>255){
	$wheelLeft=255;
      }
      if ($wheelLeft<0){
	$wheelLeft=0;
      }

      if ($wheelRight>255){
	$wheelRight=255;
      }
      if ($wheelRight<0){
	$wheelRight=0;
      }
      
      
      $hbyte=ord('a')+floor($wheelLeft/16);
      $lbyte=ord('a')+$wheelLeft-(floor($wheelLeft/16)*16);
      $xstr = chr(100).chr($hbyte).chr($lbyte);

      $hbyte=ord('a')+floor($wheelRight/16);
      $lbyte=ord('a')+$wheelRight-(floor($wheelRight/16)*16);
      $ystr = chr(101).chr($hbyte).chr($lbyte);

      stream_set_timeout($fp, 5 );
      $obj=array();
      $obj=addTask('rs232', 'tx', $dirLeft, $obj );
      $obj=addTask('rs232', 'tx', $dirRight, $obj );
      
      $obj=addTask('rs232', 'tx', $xstr, $obj );
      $obj=addTask('rs232', 'tx', $ystr, $obj );

      
      transferDataToSocket( $fp, encodeRequest( $obj ) );

      
      //sleep(50);
    }
    
    fclose( $fp );
  
  }
  
    
  $x = getUrlParam("xpos");
  $y = getUrlParam("ypos");
  echo "recived x=".$x." y=".$y.

  sendNewPosition( $x, $y );


?>
  
