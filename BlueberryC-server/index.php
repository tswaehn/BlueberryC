 
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
  
  function addTask(  $plugin, $action, $text, $obj=array()  ){
  
    $obj[] = array( $plugin => array( $action => $text ) );
    
    return $obj;
  }
  
  function encodeRequest( $obj ){
    
    $request = json_encode( $obj );
  
    return $request;
  }
  
  $fp = @fsockopen( 'localhost', 9000, $errno, $errstr, 10 );
  //$fp = @fsockopen( 'raspi', 10000, $errno, $errstr, 10 );


  extract($_GET, EXTR_OVERWRITE );

  
  $plugin = getUrlParam('plugin');
  $action=getUrlParam('action');
  $text=getUrlParam('text');
  
  $multiple = getUrlParam('multiple');
  
  if (!$fp){
    echo "error no socket <br>";
    echo $errno. " " .$errstr;

  } else {
  
    echo '<pre style="border:thin solid blue;padding:10px;">';

      stream_set_timeout($fp, 5 );
    
      
      if ($multiple != ""){
	$obj = addTask('rs232','rx','');
	$obj = addTask('rs232','tx','hello world', $obj);
	$obj = addTask('rs232','rx','', $obj);
	
	$request = json_encode( $obj );
	fwrite( $fp, $request );

      } else {
	$obj=addTask($plugin, $action, $text);
	
	fwrite( $fp, encodeRequest( $obj )  );
      }


      $info = stream_get_meta_data($fp);

      while ((!feof($fp)) && (!$info['timed_out'])) {
	$info = stream_get_meta_data($fp);

	$disp = fgets( $fp, 100 );

	if ($disp != false){
	  echo $disp;
	}
      }
      fclose( $fp );

    echo '</pre>';
  }


 
    


?>
 
 
<form action="" method="GET">
  <select name="plugin">
    <option value="master">master</option>
    <option value="rs232">rs232</option>
  </select>

  <input type="edit" name="action" value="<?php echo $action; ?>">
  <input type="edit" name="text" value="<?php echo $text; ?>">
  
<input type="submit" value="send">
</form>


<form action="" method="GET">
<input type="hidden" name="text" value="exit-socket">
<input type="submit" value="close server">
</form>


<form action="" method="GET">
<input type="hidden" name="multiple" value="exit-socket">
<input type="submit" value="multiple actions">
</form>

