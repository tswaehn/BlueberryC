<?php

 class Sketch {
    
    public $sketch;
    public $caption;
    public $md5sum;
    public $parentMd5;
    public $thumbnail;
    public $description;
    public $timestamp;
    
    
    public $children;
    
    function __construct(){
      $this->children=array();
    }
    
    function addSketch( $sketch ){
      if (strcmp( $this->md5sum,$sketch->parentMd5)==0){
	// well this is obviously the parent
	$this->children[] = $sketch;
	return 1;
      }
    
      foreach($this->children as $child){
	if ($child->addSketch( $sketch )){
	  return 1;
	}
      }
      
      return 0;
    }
    
  } // end class

  
  
  /*
      get all files and folders
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
			if (empty($sketch)){
			  $sketch = new Sketch();
			}
			$ini = new IniTools( $file );
			$sketch->sketch = $ini->getConfig("info", "sketch");
			$sketch->caption = $ini->getConfig("info", "caption");
			$sketch->description = $ini->getConfig("info", "description" );
			$sketch->thumbnail = $ini->getConfig("info", "thumbnail" );
		      }
		      if ($filename == "md5.sum"){
			if (empty($sketch)){
			  $sketch = new Sketch();
			}
			$sketch->md5sum = file_get_contents( $file );		      
		      }
		      if ($filename == "md5.parent.sum"){
			if (empty($sketch)){
			  $sketch = new Sketch();
			}
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
      get all files and folders
  */
  function dirs_recursive($dir, &$result=array() ) {
      // open handler for the directory
      $iter = new DirectoryIterator(  utf8_decode( $dir ) ); // php file access is always ISO-8859-1 
      
      $dir = str_replace( "//", "/", $dir);
      $result[] = $dir;

      foreach( $iter as $item ) {
	  // make sure you don't try to access the current dir or the parent
	  if ($item != '.' && $item != '..') {
		  if( $item->isDir() ) {
			  // call the function on the folder
			  dirs_recursive("$dir/$item", $result);
		  } else {
			  /*
			  // print files
			  $file =  $dir . "/" .utf8_encode( $item->getFilename() );
			  // remove doubles "/"
			  $file = str_replace( "//", "/", $file);
			  // store the result
			  $result[] = $file;
			  */
		  }
	  }
      }
      
      
      return $result;
  }

  
  /*
    parses all sketch folders and create a tree of
    sketch dependency/history
      
    \return: tree of sketches (sketch objects)
  */
  function readServerSideSketches(){
    
    // get the complete file structure and create sketch objects
    $sketches=dir_contents_recursive( UPLOAD_DIR );

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
    
    return $sketches;
  }
  
  /*	
      collects recursive info 
  */
  function loopChilds( $sketch, &$info = array() ){
    
    // increment child count
    $info["count"]++;
    $info["caption"] = $sketch->caption;
    if (!empty($sketch->thumbnail)){
      $info["thumbnail"] = $sketch->thumbnail;
    }

    if (!empty($sketch->description)){
      $info["description"] = $sketch->description;
    }
    
    // 
    $children = $sketch->children;
    
    if (empty($children)){
      return;
    } 
    
    foreach ($children as $child){
      loopChilds( $child, $info );
    }
    //$info["count"
    
  }
      
  function generateCondensedTreeInfo( $sketch ){
        

    $info = array();

    // prepare initial values to loop as fast as possible
    $info["count"] = 0;
    $info["caption"] = $sketch->sketch;
    $info["thumbnail"]= "";
    $info["description"]="";
    
    loopChilds( $sketch, $info );
    
    

    return $info;
  }
  
?> 
