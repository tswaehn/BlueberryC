<?php

define('URL','http://www.copiino.cc/upgrade.php');
//define('URL','http://localhost/~tswaehn/git_dev/copiino.cc/upgrade.php');

class Upgrade {


  function __construct(){
  
  
  }
  
  function request() {
    
    
    // Create Post Information
    $vars = array(
    'ip'=>'xxx.xxx.xxx.xxx',
    'some_other_info'=>'xxx',
    'ver'=>'test',
    'md5'=>'md5-sum'
    );


    // urlencode the information if needed
    $urlencoded = http_build_query($vars);

    echo 'contacting <a href="'.URL.'?'.$urlencoded.'" target="_blank">server</a> for updates ...';
    echo '<p>';
    
    if( function_exists( "curl_init" )) { 
	$CR = curl_init();
	curl_setopt($CR, CURLOPT_URL, URL);
	curl_setopt($CR, CURLOPT_POST, 1);
	curl_setopt($CR, CURLOPT_FAILONERROR, true);
	curl_setopt($CR, CURLOPT_POSTFIELDS, $urlencoded );
	curl_setopt($CR, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($CR, CURLOPT_SSL_VERIFYPEER, 0);


	$result = curl_exec( $CR );
	$error = curl_error ( $CR );


	// if there's error
	if( !empty( $error )) {
		echo $error;
		return;
	}

	curl_close( $CR );

	//parse_str($result, $output);
	// echo $output['description'];  // get description
	$obj=$this->parseResponse( $result );
	$this->display( $obj );
	
    } else {
      echo "cURL not installed!";
    
    }
  }

  
  function parseResponse( $response ){
    
    $text='';
    
    if (preg_match("/success/", $response )){
      $response=preg_replace('/success/', '', $response);
    } else {
      echo 'failed to connect<br>';
      return $text;
    }
    //echo $response;
    
    $obj=json_decode( $response, true );
    
    //print_r( $obj );
    //echo '<p>';
    
    return $obj;
  }
  
  function display( $objects ){
    
    foreach ($objects as $type=>$obj){

      if (empty($obj)){
	continue;
      }
      
      $date = $obj['date'];
      $list = $obj['list'];
    
      echo '<p>';
      echo '"'.$type.'" last modified on '.$date.'<br>';
      
      echo '<ul>';
	foreach ($list as $item){
	  $ver=$item['ver'];
	  $file=$item['file'];
	  $link=$item['link'];
	  
	  echo '<li>';
	  echo 'install <a href="'.LinkToMe('ask_install').'&file='.$file.'&ver='.$ver.'&link='.$link.'">'.$ver.'</a>';
	  echo ' '.$file;
	  echo '</li>';
	}
	echo '</ul>';
    }
  
  
  
  }
  
};







?>
