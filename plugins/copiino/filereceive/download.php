<?php

  /*
  \todo:
  
  
  \done:
  
    wenn eine sketch mehrfach ohne änderungen ge-uploaded wird, verliert
    es die md5parent information
    
  */   
  include("serverconfig.php");
  include("xIniTools.php");
  include("xSketch.php");

  define('START_TAG', '<CONTENTS>');
  define('END_TAG', '</CONTENTS>');
  define('HEADER_S_TAG', '<HEADER>' );
  define('HEADER_E_TAG', '</HEADER>');
  

  if (DEBUG){
    echo "<pre>";
  }
  
  // read all sketches 
  $sketches = readServerSideSketches();
  
  // display the tree
  if (DEBUG){
    print_r( $sketches );
  }

  $json_content = json_encode( $sketches );

  $header= array( "version"=>"1.0",
		  "crc"=>md5( $json_content )
		  );
		  
  $json_header = json_encode( $header );
  
  $contents = HEADER_S_TAG.$json_header.HEADER_E_TAG. START_TAG.$json_content.END_TAG;
  
  // return the json stream for client processing
  echo $contents;

  if (DEBUG){
    echo "</pre>";
  }
?>

