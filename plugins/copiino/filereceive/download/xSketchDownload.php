<?php

class SketchDownload {
  
  
  /*
      parse all files and folders and create sketch objects
      
  */
  function dir_contents_recursive($dir, &$result=array() ) {
      // open handler for the directory
      $iter = new DirectoryIterator(  utf8_decode( $dir ) ); // php file access is always ISO-8859-1 

      $sketch="";
      
      foreach( $iter as $item ) {
          // make sure you don't try to access the current dir or the parent
          if ($item != '.' && $item != '..') {
                  if( $item->isDir() ) {
                          // call the function on the folder
                          $this->dir_contents_recursive("$dir/$item", $result);
                  } else {
                      $filename = $item->getFilename();
                      if (empty($sketch)){
                        $sketch = new Sketch();
                      }
                      
                      // print files
                      $file =  $dir . "/" .utf8_encode( $item->getFilename() );
                      // remove doubles "/"
                      $file = str_replace( "//", "/", $file);
                      
                      if ($filename == "sketch.ini"){
                        $ini = new IniTools( $file );
                        $sketch->sketch = $ini->getConfig("info", "sketch");
                        $sketch->caption = $ini->getConfig("info", "caption");
                        $sketch->description = $ini->getConfig("info", "description" );
                        $sketch->thumbnail = $ini->getConfig("info", "thumbnail" );
                      }
                      if ($filename == "sketch-info.json"){
                        $contents = file_get_contents( $file );
                        $info = json_decode( $contents, true);
                        $sketch->md5sum=$info["md5sum"];                        
                        $sketch->parentMD5=$info["parentMD5"];
                        $sketch->timestamp=$info["timestamp"];
                        $sketch->user=$info["user"];
                        $sketch->revision=$info["revision"];
                        $sketch->commitMsg= $info["commitMsg"];
                        $sketch->commitType= $info["commitType"];
                      }

                  }
          }
      }

      // store the result
      if (!empty($sketch)){
        $result[] = $sketch;
      }
      
      return $result;
  }

  
  /*
    parses all sketch folders and create a tree of
    sketch dependency/history
    
    with "filter" it is possible to select only a subset of
    available sketches
    
    \return: tree of sketches (sketch objects)
  */
  function readServerSideSketches( $user="", $filter=""){
    
    // decide user space to search at
    if( empty($user)){
      echo "search globally";
      // get the complete file structure and create sketch objects
      $sketches= $this->dir_contents_recursive( UPLOAD_DIR.'/' );

    } else {
      echo "search for user ".$user;
      // get the complete file structure and create sketch objects
      $sketches= $this->dir_contents_recursive( UPLOAD_DIR.'/'.$user.'/' );
    }
    
    // do some prefiltering
    if (!empty($filter)){
      echo "filtering for ".$filter;
    
      // filter by selected sketch
      foreach ($sketches as $key=>$sketchTest){
        if (strcmp( $sketchTest->sketch, $filter) != 0){
          // remove from list
          unset($sketches[$key]);
        }
      
      }    
    } else {
      echo "no filter active";
    }
    
    // connect the sketches by finding parents and their childs
    // resulting in a tree
    foreach ($sketches as $key=>$sketchA){
      foreach ($sketches as $sketchB){
        if ($sketchA != $sketchB){
          if ($sketchB->addSketch( $sketchA )){
            unset($sketches[$key]);
            break;
          }
        }
      }
    }
    
    // find same sketches and create virtual parent
    foreach ($sketches as $keyA=>&$sketchA){
      if (empty($sketchA)){
        continue;
      }
      foreach ($sketches as $keyB=>&$sketchB){
        if (empty($sketchB)){
          continue;
        }       
        if ($keyA != $keyB){
          // if sketchA is not sketchB
          if ( strcmp($sketchA->sketch,$sketchB->sketch)==0){
            // but the sketch name is the same, ... join them
            echo $sketchA->sketch."( ".$sketchA->md5sum." ) and ".$sketchB->sketch."( ".$sketchB->md5sum." ) are the same<br>";
            $sketches[$keyA]->children[] = $sketchB;
            $sketches[$keyB]=null;        
          }
        }
      }
    
    }
    
    $sketches = array_filter( $sketches );

    //     
    return $sketches;
  }
  
  

 
  function returnProjectsOverview( $scope, $user, $filter){
  
    // remove user information if we are on global scope
    if($scope == "global"){
      $user="";
    }
    
    // read all sketches 
    $sketches = $this->readServerSideSketches($user, $filter);

    return $sketches;
    
  }
  
  
  
  
  
  function returnSketch( $user, $sketch, $md5sum ){
    
    $data= array();
    
    // check if all data is available
    if (empty($user) || empty($sketch) || empty($md5sum) ){
      return $data;
    }
  
    // set default fields
    $data["user"]= $user;
    $data["sketch"]= $sketch;
    $data["md5sum"]= $md5sum;
    $data["result"]= "";
    
    // check for sketch folder
    $folder= UPLOAD_DIR.$user."/".$sketch."/".$md5sum."/";
    if (!file_exists($folder)){
      $data["result"]= "folder does not exist". $folder;
      return $data;
    }
    
    // read sketch info
    $infofile=$folder."sketch-info.json";
    if (file_exists($infofile)){
      $info = json_decode( file_get_contents( $infofile ), true );
      // remove folder information
      foreach( $info["files"] as $key=>$filename ){
        $filename= basename( $filename );
        $info["files"][$key]=$filename;
      }
      
      $data["timestamp"]= $info["timestamp"];
      $data["revision"]= $info["revision"];
      $data["commitMsg"]= $info["commitMsg"];
      $data["commitType"]= $info["commitType"];
      $data["files"]= $info["files"];
      
      
      // go through the file list and add each file 
      foreach( $info["files"] as $filename){
        $fullfile= utf8_encode($folder. $filename);
        if ( file_exists( $fullfile )){
          $contents= file_get_contents( $fullfile );
          $data[$filename]= base64_encode( $contents );
        } else {
          $data[$filename]= "does not exist";
        }
      }
      $data["result"]="thanks";
    }
    
    
    
    
  
    return $data;
  }
 
   
}



?>
