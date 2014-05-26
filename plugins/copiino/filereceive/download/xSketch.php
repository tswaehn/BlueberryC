<?php

 class Sketch {
    
    public $sketch;
    public $caption;
    public $md5sum;
    public $parentMd5;
    public $thumbnail;
    public $description;
    public $timestamp;
    public $contributors;
    
    
    public $children;
    
    function __construct(){
      $this->children=array();
      $this->contributors=array("tswaehn" , "andreas" );
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
      
    \return: tree of sketches (sketch objects)
  */
  function readAllServerSideSketches(){
    
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
    provides all sketches of a single project
    
    parses sketches on server which are located in
    a given project path

    \return: tree of sketches (sketch objects)
      
  */
  function readSpecificServerSideSketches( $sketch ){
  
    // get the complete file structure and create sketch objects
    $sketches=dir_contents_recursive( UPLOAD_DIR );
    
    // filter by selected sketch
    foreach ($sketches as $key=>$sketchTest){
      if (strcmp( $sketchTest->sketch, $sketch) != 0){
	// remove from list
	unset($sketches[$key]);
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
    
    return $sketches;
  
  }
  
  /*	
      collects recursive info 
  */
  function loopChilds( $sketch, &$info = array() ){
    
    // increment child count
    $info["count"]++;
    
    // if this sketch is newer than others
    if ( $sketch->timestamp > $info["timestamp"]){
      $info["caption"] = $sketch->caption;
      $info["thumbnail"] = $sketch->thumbnail;
      $info["description"] = $sketch->description;
      $info["timestamp"]= $sketch->timestamp;
      foreach ($sketch->contributors as $contributor){
	$info["contributors"][]=$contributor;
      }
      $info["contributors"] = array_unique($info["contributors"]);
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
    $info["timestamp"] = 0;
    $info["sketch"]= $sketch->sketch;
    
    loopChilds( $sketch, $info );
    
    return $info;
  }
 
  /*
      timestamp compare
  */
  function cmpSketchTime( $a, $b ){
    if ($a["timestamp"] > $b["timestamp"]){
      return -1;
    }
    if ($a["timestamp"] == $b["timestamp"]){
      return 0;
    }
    if ($a["timestamp"] < $b["timestamp"]){
      return +1;
    }

  }
      
  function prepareSketchOverview( $sketches ){
    
    $overview = array();
    
    foreach( $sketches as $sketch ){
      $info = generateCondensedTreeInfo( $sketch );
      
      $overview[] = $info;

    }
    
    // sort
    uasort( $overview, "cmpSketchTime" );
    
    return $overview;
  
  }
  
  function renderSketchOverview(){

    // read all sketches 
    $sketches = readAllServerSideSketches();

    //http://stackoverflow.com/questions/23413899/php-sorting-moving-objects-and-join-remove-same-objects

    $test = array( "a", "b", "c", "b", "c" );
    
    foreach ($test as $keyA=>$valueA){
      
      if (!isset($test[$keyA])){
	continue;
      }
      echo "I am at item ".$valueA." [".$keyA."]<br>";
      
      foreach ($test as $keyB=>$valueB){
	if (!isset($test[$keyB])){
	  continue;
	}      
	if ($keyA != $keyB){
	  // if not comparing to itself
	  echo "=> comparing to ".$valueB." [".$keyB."]";
	  if (strcmp($valueA,$valueB)==0){
	    // but is the same string, ... join and remove
	    echo "-- joined and removed [".$keyB."]";
	    $test[$keyA]=$valueA.$valueB;
	    unset($test[$keyB]);	  
	  }
	  echo "<br>";
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
    $overview = prepareSketchOverview( $sketches );
    
    
    foreach( $overview as $info ){
      
      echo '<div id="sketch">';
      echo '<a href="?sketch='.$info["sketch"].'">'.$info["caption"]."</a> (".$info["count"].") <br>";
      echo timeDiffToString( time() - $info["timestamp"] )."<br>";
      echo implode(",", $info["contributors"])."<br>";
      echo '<span id="description">'.$info["description"]."</span>";
      echo '</div>';

    }

    // display the tree
    if (DEBUG){
      echo '<p style="clear:left">';
      echo "<pre>";
      print_r( $sketches );
      echo "</pre>";
    }    
    
  }
  
  function renderAllChilds( $sketches ){
    
    
    echo '<div id="history-container">';
    
      if (empty($sketches->children)){
	echo '<div id="history-sketch-newest">';	
      } else {
	echo '<div id="history-sketch">';	
      }
      echo '<a href="./?">'.$sketches->caption."</a> ";
      echo $sketches->md5sum." ";
      echo timeDiffToString( time() - $sketches->timestamp)."<br>";
      echo $sketches->description."<br>";
      echo implode(",", $sketches->contributors);
      echo '</div>';
      
      foreach ($sketches->children as $child){
	renderAllChilds( $child );
      }
      
    echo '</div>';
        
  }
  
  function renderSingleSketch( $sketch ){
  
    $sketches = readSpecificServerSideSketches( $sketch );

    foreach ($sketches as $sketch){
      renderAllChilds( $sketch );
    }
    
    echo "<pre>";
    print_r( $sketches );
    echo "</pre>";
  }
  
  
?> 
