<?php

class SketchSync {
  
  
  function __construct(){
  
  
  }
  
  
  function prepareFileArgs( $filenames, $additionalArgs ){
    
    // thread even single files as array
    if (! is_array( $filenames )){
      $filenames=array( $filenames );
    }
    
    $file_args = array();
    $file_count = 0;

    foreach ($filenames as $filename ){
      // expand to full path (ex. './test.png' )
      $file_with_full_path = realpath( $filename );
      // check existence
      if (!is_file($file_with_full_path)){
	echo 'cannot send '.$filename.'<br>';
	continue;
      }
      // add to list
      //$file_args['file'] = new CurlFile( $filename, $mimetype, $postname );
      //$file_args['file'] = curl_file_create( $filename );
      $file_args['file'.$file_count]= '@'.$file_with_full_path;
      $file_count++;
    }
    
    $file_args['file_count']=$file_count;
    
    // additional 
    foreach ($additionalArgs as $key=>$value){
      $file_args[$key]=$value;
    }
    
    return $file_args;
  }
  
  /*
    
  */
  function sendFilesViaCurl( $target_url, $filenames, $additionalArgs ){

    // generate full information about uploaded-files
    $file_args = $this->prepareFileArgs( $filenames, $additionalArgs );
    
    // start setup curl
    $curl_handle = curl_init();
    curl_setopt( $curl_handle, CURLOPT_URL, $target_url );
    curl_setopt( $curl_handle, CURLOPT_POST, 1);
    curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $file_args );
    curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $curl_handle, CURLOPT_FAILONERROR, true);
		
    // finally send
    $result = curl_exec( $curl_handle );
    curl_close( $curl_handle );
    
    return $result;  
  
  }

  
  function sendFiles( $filenames, $args=array() ){
    
    $target_url = 'http://localhost/~tswaehn/git_dev/BlueberryC-retro/plugins/copiino/filereceive/accept.php';
    
    $result = $this->sendFilesViaCurl( $target_url, $filenames, $args );
    
    // debug output
    echo 'server result:<br>';
    echo $result;
    
    return $result;
    
  }

};

?>
