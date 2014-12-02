<?php


  define( "REMOTE_SERVER_DL", 'http://homer/~tswaehn/git_dev/BlueberryC-retro/plugins/copiino/filereceive/download.php');

    
  include("xSketch.php");
  include("xSketchUpload.php");
  include( PLUGIN_DIR."sketchbrowser/xBrowser.php");
  include("displayProjects.php");
  include("displaySingleProject.php");
  include("displaySketch.php");
  include("displayDownload.php");
  
  include( PLUGIN_DIR."config/jsonSettings.php");
  include( PLUGIN_DIR."config/copiinoSettings.php");
  
  include( PLUGIN_DIR."sketchbrowser/xSketchConfig.php" );
  
  

  function curlDownload( $args = array() ){
  
    $target_url = REMOTE_SERVER_DL;
    
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
  
    //echo "<pre>".$result."</pre>";
    
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

        /*
        echo "<pre>";
           print_r( $array );
        echo "</pre>";
          */

    // remove the array and printout 
    $result = substr_replace( $result, "", $start, $end-$start );
    //echo '<div id="debug">'.$result.'</div>';
    
    return $array;
  }
  
  function arrayToSketches( $array ){
    
    if (!is_array( $array)){
      return array();
    }
    
    $sketches = array(); 
    
    foreach ($array as $key=>$item){
      
      $sketch = new Sketch();
      $sketch->createFromArray( $item );
      
      $sketches[$key] = $sketch;
    }
  
    return $sketches;
  }
  
  function downloadDataFromServer( $args ){
      
      if (!is_array($args)){
        $args=array( $args );
      }
      
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
  
      return $array;
  }
  
  
  $action = getUrlParam( "action" );
  $sketch = getUrlParam( "sketch" );
  $scope = getUrlParam( "scope" );



  switch ($action){
    
    case 'show_project': 
            $args = array("action"=> "project", "sketch"=>$sketch, "scope"=>$scope );
            
            $data = downloadDataFromServer( $args );
            
            if (isset($data["account"])){
                // telling that account is invalid
                echo 'Invalid copiino.cc account please check settings.';
            } else {

              $sketches = arrayToSketches( $data );
              renderSingleProject( $sketches, $scope );
            }
            
            break;

    case 'show_single':
            $user= getUrlParam("user");
            $md5sum= getUrlParam("md5sum");
            $args = array("action"=> "sketch", "sketch"=>$sketch, "scope"=>$user, "md5sum"=>$md5sum );
            
            $data = downloadDataFromServer( $args );
            
            if (isset($data["account"])){
                // telling that account is invalid
                echo 'Invalid copiino.cc account please check settings.';
            } else {

              renderSketch( $data );
            }
    
            break;
            
    case 'download':
            $user= getUrlParam("user");
            $md5sum= getUrlParam("md5sum");
            $args = array("action"=> "sketch", "sketch"=>$sketch, "scope"=>$user, "md5sum"=>$md5sum );
            
            $data = downloadDataFromServer( $args );

            if (isset($data["account"])){
                // telling that account is invalid
                echo 'Invalid copiino.cc account please check settings.';
            } else {

              downloadSketch( $data );
            }
    
            break;

            
    default:
    
      // overview
      
      echo "<h3>Sketch Cloud</h3>";
        
      if ($scope=="global"){
        echo 'switch to <a href="'.linkToMe('').'">my cloud</a><br>';
      } else {
        echo 'switch to <a href="'.linkToMe('').'&scope=global">global cloud</a><br>';
      }
      
      $args= array( "action"=>"overview", "scope"=>$scope );
      
      $data = downloadDataFromServer( $args );
      
      if (isset($data["account"])){
          // telling that account is invalid
          echo 'Invalid copiino.cc account please check settings.';          
      } else {
      
        $sketches = arrayToSketches( $data );
    
        overview( $sketches, $scope );
      }
  }

?>
