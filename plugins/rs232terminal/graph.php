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

  
  function parseString( $str ){
    // remove (rs232)
    $str=preg_replace("/\(.*\)/", "", $str );
    
    // explode by channels
    $channels = preg_split( "/\n/", $str, -1, PREG_SPLIT_NO_EMPTY );
    
    // set channel nr to each dataset
    $dataset=array();
    foreach( $channels as $ch ){
      $channel = preg_split( "/\:/", $ch, -1, PREG_SPLIT_NO_EMPTY );
      $nr=$channel[0];
      $data=$channel[1];
      $data = preg_split( "/\,/", $data, -1, PREG_SPLIT_NO_EMPTY );
      $dataset[] = $data ;
    }
    
    return $dataset;
  }
  
  $data=array();
  $str="";
  
  $fp = @fsockopen( 'localhost', 9000, $errno, $errstr, 5000 );
  
  if (!$fp){
    //echo "error no socket <br>";
    //echo $errno. " " .$errstr;

    $data[] = 0;
    
  } else {
    
      $action="sensorlog";
      $obj=addTask('rs232', $action, '');
      
      $str = transferDataToSocket( $fp, encodeRequest( $obj )  );

/*
      $info = stream_get_meta_data($fp);

      while ((!feof($fp)) && (!$info['timed_out'])) {
	$info = stream_get_meta_data($fp);

	$disp = fread( $fp, 1024 );

	if ($disp != false){
	  $str .= $disp;
	}
      }
      fclose( $fp );
  */
    echo $str;
    $data = parseString( $str );
    
  
    //print_r($data);

    // http://www.goat1000.com/svggraph-using.php
    include('../../3rdParty/SVGGraph/SVGGraph.php');
    
    $settings['marker_size']=2;
    $settings['stroke_width']=1;
    //$settings['fill_under']=array(true,true);
    
    $graph = new SVGGraph(600,300, $settings);
    $graph->Values($data);
    $graph->Render('MultiLineGraph');    
  }


?>
