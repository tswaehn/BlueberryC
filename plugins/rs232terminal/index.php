<?php
  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );


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

  
  echo "<h2>hello serial socket user</h2>";

  $fp = @fsockopen( 'localhost', 10000, $errno, $errstr, 10 );

  echo "<div style='border:thin solid green;padding:10px'>";

  $text=getUrlParam('text');

  if (!$fp){
    echo "error no socket <br>";
    echo $errno. " " .$errstr;

  } else {
    echo "<pre>";
    echo "TX>".$text;
    echo "<br>";

    fwrite( $fp, $text );

    stream_set_timeout($fp, 0,1000000);

    echo "RX>";

    $info = stream_get_meta_data($fp);

    while ((!feof($fp)) && (!$info['timed_out'])) {
      $info = stream_get_meta_data($fp);

      $disp = fgets( $fp, 100 );

      if ($disp != false){
        echo $disp;
      }
    }
    fclose( $fp );
    
    echo "</pre>";
    echo "<p> done";
  }

  echo "</div>";

  echo "<p>";

  echo '<form action="'.linkToMe('send').'" method="post">';
  echo "<textarea name='text' cols='50', rows='10'>".$text."</textarea>";
  echo "<input type='submit' value='send to controller'>";
  echo "</form>";


    


?>
 
