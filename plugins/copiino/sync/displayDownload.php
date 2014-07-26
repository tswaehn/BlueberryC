<?php

  function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
      foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
      }
      return rmdir($dir);
  } 
  

  function downloadSketch( $data ){
    echo "<pre>";
    //print_r( $data );   
    echo "</pre>";
    
    // check if the return result is as desired
    if ($data["result"] != "thanks" ){
      return;
    }
    
    $user= $data["user"];
    $timestamp= $data["timestamp"];
    $revision= $data["revision"];
    
    echo "<h3>Download Sketch from Cloud</h3>";
    
    // unwrap files and store them in temporary folder
    /*
    $tmpFiles=array();
    foreach( $data["files"] as $filename ){
      // create a unique temp file
      $tmpName= tempnam(sys_get_temp_dir(), 'Copiino');
      $tmpFiles[$filename]= $tmpName;
      file_put_contents( $tmpName, base64_decode( $data[$filename] ));
    }
    */

    // load sketch ini file
    $config= SketchConfig::loadByText( base64_decode( $data["sketch.ini"]) );

    // set variables
    $sketch= $config->getConfig("info", "sketch");
    $caption= $config->getConfig("info", "caption");
    $projectID= $config->getConfig("info", "sketch");
    $serverMD5= $data["md5sum"];
    $description= $config->getConfig("info", "description");
    $cpp= $config->getConfig("cpp","file");

    
    echo "Sketch:".$sketch."<br>";
    echo "user:".$user."<br>";
    echo "revision:".$revision."<br>";
      
    // local folder
    $targetFolder= PLUGIN_DIR.'sketches/'.$sketch.'/';
  
    // check if sketch exists locally
    if (file_exists( $targetFolder )){
      echo "project exists locally<br>";
      // check if local project is in sync with server
      echo "uploading existing project<br>";
      $browser= new Browser();
      $browser->uploadSketch( $sketch ); 

      // clear local folder
      echo "clearing local folder<br>";
      delTree( $targetFolder );
    } else {
      echo "download new sketch<br>";
    }
    
    // create target folder
    if (!mkdir($targetFolder)){
      echo "failed to create target folder<br>";
      echo "stopped download<br>";
      return;
    }
    
    // decode and copy all files to the folder
    echo "copy files to target folder<br>";
    foreach( $data["files"] as $filename ){
      // create a unique temp file
      $targetFile= $targetFolder.$filename;
      file_put_contents( $targetFile, base64_decode( $data[$filename] ));
    }
    
    // create md5sum file for parent information
    file_put_contents( $targetFolder."md5.sum", $serverMD5 );
    
    // done
    echo "done<br>";
    
  }








?>
