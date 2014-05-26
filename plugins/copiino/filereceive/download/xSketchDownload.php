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
                          dir_contents_recursive("$dir/$item", $result);
                  } else {
                      $filename = $item->getFilename();
                      if (empty($sketch)){
                        $sketch = new Sketch();
                        $sketch->timestamp= filemtime( $dir );
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
                      if ($filename == "md5.sum"){
                        $sketch->md5sum = file_get_contents( $file );                 
                      }
                      if ($filename == "md5.parent.sum"){
                        $sketch->parentMd5 = file_get_contents( $file );                      
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
  function readServerSideSketches( $filter = ""){
    
    // get the complete file structure and create sketch objects
    $sketches=dir_contents_recursive( UPLOAD_DIR );

    // do some prefiltering
    if (!empty($filter)){
    
      // filter by selected sketch
      foreach ($sketches as $key=>$sketchTest){
        if (strcmp( $sketchTest->sketch, $filter) != 0){
          // remove from list
          unset($sketches[$key]);
        }
      
      }    
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
  
  

 
  function returnProjectsOverview( $filter = "" ){

    // read all sketches 
    $sketches = $this->readServerSideSketches($filter);

    jsonCurlReturn( $sketches );
    
  }
  
  
 
   
}



?>
