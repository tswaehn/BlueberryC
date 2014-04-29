<link rel="stylesheet" type="text/css" href="format.css">

<pre>
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

  // read all sketches 
  $sketches = readServerSideSketches();
  

  
  foreach( $sketches as $sketch ){
    $info = generateCondensedTreeInfo( $sketch );
    
    echo '<div id="sketch">';
    echo $info["caption"]." ".$info["count"];
    echo '</div>';
  
  }
  
  // display the tree
  if (DEBUG){
    echo "<p>";
    echo "<pre>";
    print_r( $sketches );
    echo "</pre>";
  }
  
?>
</pre>