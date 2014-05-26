<?php


  define( "REMOTE_SERVER", 'http://homer/~tswaehn/git_dev/BlueberryC-retro/plugins/copiino/filereceive/download.php');

    
  include("xSketch.php");
  include("display.php");
  
  include( PLUGIN_DIR."config/jsonSettings.php");
  include( PLUGIN_DIR."config/copiinoSettings.php");

  function curlDownload( $args = array() ){
  
    $target_url = REMOTE_SERVER;
    
    // start setup curl
    $curl_handle = curl_init();
    curl_setopt( $curl_handle, CURLOPT_URL, $target_url );
    curl_setopt( $curl_handle, CURLOPT_POST, 1);
    curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $args );
    curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $curl_handle, CURLOPT_FAILONERROR, true);
                
    // finally send
    $result = curl_exec( $curl_handle );
    curl_close( $curl_handle );
  
    $start=strpos( $result, "<CONTENTS>" );
    $end = strpos( $result, "</CONTENTS>" );
    
    if (($start === false) || ($end===false)){
      // error
      return "";
    } 
    
    // cut the array
    $contents = substr( $result, $start, $end-$start );
    
    $contents = preg_replace( "/<CONTENTS>|<\/CONTENTS>/", "", $contents );
    
    $array = json_decode( $contents, true );
    
    // remove the array and printout 
    $result = substr_replace( $result, "", $start, $end-$start );
    //echo '<div id="debug">'.$result.'</div>';
    
    return $array;
  }
  
  function arrayToSketches( $array ){
    
    $sketches = array(); 
    
    foreach ($array as $key=>$item){
      
      $sketch = new Sketch();
      $sketch->createFromArray( $item );
      
      $sketches[$key] = $sketch;
    }
  
    return $sketches;
  }
  
  $action = getUrlParam( "action" );
  $sketch = getUrlParam( "sketch" );
  
  switch ($action){
    
    case 'project': 
            $args = array("action"=> "project", "sketch"=>$sketch);
            
            // load copiino account settings
            $settings = new CopiinoSettings();
            $user= $settings->getConfig( "user" );
            $email = $settings->getConfig( "email" );
            $pwd = $settings->getConfig( "pwd" );
            
            // add copiino account
            $args["user"] = $user;
            $args["email"] = $email;
            $args["pwd"] = $pwd;
    
            $array = curlDownload( $args );
            
            if (isset($array["account"])){
                // telling that account is invalid
                echo 'Invalid copiino.cc account please check settings.';
            } else {

              $sketches = arrayToSketches( $array );
          
              renderSingleProject( $sketches );
            }
            
            break;

    case 'download':
            
            $md5sum=getUrlParam("md5sum");
            echo "Please confirm to download ".$sketch."<br>";
            echo "Revision ".$md5sum;    
    
            break;
    default:
      $args= array( "action"=>"overview" );
      
      // load copiino account settings
      $settings = new CopiinoSettings();
      $user= $settings->getConfig( "user" );
      $email = $settings->getConfig( "email" );
      $pwd = $settings->getConfig( "pwd" );
      
      // add copiino account
      $args["user"] = $user;
      $args["email"] = $email;
      $args["pwd"] = $pwd;
      
      $array = curlDownload( $args );
      
      if (isset($array["account"])){
          // telling that account is invalid
          echo 'Invalid copiino.cc account please check settings.';          
      } else {
      
        $sketches = arrayToSketches( $array );
    
        overview( $sketches );
      }
  }

?>
