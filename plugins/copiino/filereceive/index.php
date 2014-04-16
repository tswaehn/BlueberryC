<pre>
<?php

  /*
  \todo:
  
    wenn eine sketch mehrfach ohne änderungen ge-uploaded wird, verliert
    es die md5parent information
    
  */   
  include("serverconfig.php");
  include("xIniTools.php");
  
  class Sketch {
    
    public $sketch;
    public $caption;
    public $md5sum;
    public $parentMd5;
    public $thumbnail;
    public $description;
    
    
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
      
  $sketches=dir_contents_recursive( UPLOAD_DIR );
    
  // generate sketchinfo
  $root = new Sketch();
  $root->md5sum = 0;
  
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
  
  print_r( $sketches );
  /*
  foreach ($sketches as $sketch){
    $root->addSketch( $sketch );
  
  }
  
  print_r( $root );
  print_r( $sketches );

*/




?>

</pre>