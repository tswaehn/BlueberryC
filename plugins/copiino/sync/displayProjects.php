<?php



  /*    
      collects recursive info 
  */
  function loopChildren( $sketch, &$info = array() ){
    
    // increment child count
    $info["count"]++;
    
    // if this sketch is newer than others
    if ( $sketch->timestamp > $info["timestamp"]){
      $info["caption"] = $sketch->caption;
      $info["thumbnail"] = $sketch->thumbnail;
      $info["description"] = $sketch->description;
      $info["timestamp"]= $sketch->timestamp;
      $info["revision"]= $sketch->revision;
      $info["user"]= $sketch->user;
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
      loopChildren( $child, $info );
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
    $info["revision"]= -1;
    $info["user"]="";
    
    loopChildren( $sketch, $info );
    
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
  

  function overview( $sketches, $scope ){
    
    // extract 
    $overview = prepareSketchOverview( $sketches );
    
    
    foreach( $overview as $info ){
      
      echo '<div id="sketch_item">';
      echo '<span id="caption"><a href="'.linkToMe("show_project").'&sketch='.$info["sketch"].'&scope='.$scope.'">'.$info["caption"]."</a></span> (".$info["count"]." revs) ";
      echo timeDiffToString( time() - $info["timestamp"] )." ";
      echo 'by '.$info["user"]."<br>";
      echo '<span id="description">'.$info["description"]."</span>";
      echo '</div>';

    }

    return $overview;
  }  
    

    


?>
