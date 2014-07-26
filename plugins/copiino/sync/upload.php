<?php

  include( PLUGIN_DIR.'sync/xSketchUpload.php');

  include( PLUGIN_DIR."/config/jsonSettings.php");
  include( PLUGIN_DIR."/config/copiinoSettings.php" );  
  

  function prepareUploadSketch( $sketch ){

    $config= SketchConfig::loadFromSketchFolder( $sketch );
    $caption = $config->getConfig('info','caption');
    
    echo "You will upload the following sketch. <p>";
    echo '<span id="caption">'.$caption."</span><br>";
    echo "<p>";
    echo '<form action="'.linkToMe("upload").'&do=upload&sketch='.$sketch.'" method="post">';
    echo '<select name="committype" >';
      echo '<option value="mod" selected="selected">sketch edit</option>';
      echo '<option value="new">new sketch</option>';      
      echo '<option value="add">added files</option>';      
      echo '<option value="del">removed files</option>';      
      echo '<option value="setup">changed sketch setup</option>';      
    echo '</select>';
    echo '<br>';
    echo '<textarea name="commitmsg" cols="40" rows="4"></textarea>';
    echo '<input name="submit" type="submit" value="Upload">';
    echo '</form>';
    
    echo 'Please provide information about your changes.';
  }
  
  

  function uploadSketch( $sketch ){
    
    $commitMsg= getUrlParam( "commitmsg" );
    $commitType= getUrlParam( "committype" );
    
    $sync = new SketchSync();
    $config= SketchConfig::loadFromSketchFolder( $sketch );

    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = PLUGIN_DIR.'sketches/'.$config->getConfig('info','thumbnail');
    $wiring = PLUGIN_DIR.'sketches/'.$config->getConfig('info','wiring');
    $cpp = $config->getConfig('cpp','file');

    $md5File=  PLUGIN_DIR.'sketches/'.$sketch.'/'.'md5.sum';
    $old_md5sum='0';
    // display old md5sum
    if (is_File($md5File)){
      $old_md5sum= file_get_contents( $md5File );
      echo 'old md5 is: '.$old_md5sum.'<br>';
    }
    
    echo '<pre>';
    echo 'uploading '.$caption.' ('.$sketch.')<br>';
      
    $files = array();
    $files[] = $config->getFilename();
    $files[] = PLUGIN_DIR.'sketches/'.$sketch.'/'.'default.ino';
    $files[] = $thumbnail;
    $files[] = $wiring ;
    foreach ($cpp as $file ){
      $files[] = PLUGIN_DIR.'sketches/'.$sketch.'/'.$file;
    }

    // load copiino account settings
    $settings = new CopiinoSettings();
    $user= $settings->getConfig( "user" );
    $email = $settings->getConfig( "email" );
    $pwd = $settings->getConfig( "pwd" );

    $args = array();
    $args['user']= $user;
    $args['email']= $email;
    $args['pwd']= $pwd;
    $args['commitmsg']= $commitMsg;
    $args['committype']= $commitType;
    
    $args['sketch']=$sketch;
    $args['parentmd5']=$old_md5sum;
    
    
    // send files and data
    $result = $sync->sendFiles( $files, $args );
    
    if ($result["error"]){
    
      echo $result["msg"];

    } else {
      // parse for the new md5sum
      if (isset($result["data"]["md5Parent"])){
      
        $md5sum = $result["data"]["md5Parent"];
        
        if (!empty( $md5sum )){
          $md5File=  PLUGIN_DIR.'sketches/'.$sketch.'/'.'md5.sum';
        
          if (file_put_contents( $md5File, $md5sum ) == false){
            echo "unable to write to file ".$md5File.'<br>';
          }     
          
          echo 'new md5: '.$md5sum.'<br>';
        }
      } else {
        echo 'error: cannot receive new md5sum<br>';
      }
    
    }
    
    
    echo '</pre>';
    
  }

  
  echo "<h3>Upload Sketch to Cloud</h3>";
  
  
  $do= getUrlParam( "do" );
  $sketch= getUrlParam( "sketch" );
  
  
  switch ($do){
  
    case "upload": uploadSketch( $sketch );  break;
    
    
    default:
      if (!empty( $sketch )){
        prepareUploadSketch( $sketch );
      }
  
  }
  
  
  
  














?>
