<?php

  

  function renderSketch( $data ){
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
    
    $thumbnail= basename( $config->getConfig("info", "thumbnail") );
    $wiring= basename( $config->getConfig("info", "wiring") );
    
    
    // prepare inline thumbnail
    $extension= pathinfo( $thumbnail, PATHINFO_EXTENSION);
    $thumbnail = 'data:image/'.$extension.';base64,'.$data[$thumbnail];
  
    // prepare wiring
    $extension= pathinfo( $wiring, PATHINFO_EXTENSION);
    $wiring = 'data:image/'.$extension.';base64,'.$data[$wiring];
    
    // options
    $options = '<a href="'.linkToMe('download').'&sketch='.$sketch.'&user='.$user.'&md5sum='.$serverMD5.'">download</a> ';
    
    // prepare code
    $code= base64_decode( $data["default.ino"] );
    
    echo '<h3>Cloud Sketch</h3>';
    
    echo '<div id="sketch_view">';
    echo '<div id="sketch_icon"><img src="'.$thumbnail.'" width="64" height="64" /></div>';
    echo '<span id="sketch_caption">'.$caption.'</span>';
    echo '<span id="">(rev '.$revision.' by '.$user.') '.timeDiffToString(time()-$timestamp)."</span><br>";
    echo '<span id="sketch_options">'.$options.'</span><br>';

    echo '<div id="sketch_projectid">';
    echo '<span id="sketch_projectid">projectID '.$projectID.'</span><br>';
    echo '<span id="sketch_md5">server MD5 '.$serverMD5.'</span><br>';
    echo '</div>';
    
    echo '<span id="sketch_description">'.$description.'</span><br>';
    echo '<span id="sketch_code">'.$code.'</span><br>';
    echo '<div id="sketch_wiring"><img src="'.$wiring.'" width="400" /></div><br>';

    echo '<span id="sketch_description">';
    if (!empty($cpp)){
      echo 'additional library files for this sketch:';
      echo '<ul>';
      foreach ($cpp as $file){
        echo '<li>'.$file.'</li>';
      }
      echo '</ul>';
    }
    echo '</span><br>';

    echo "</div>";    
  
  }








?>
