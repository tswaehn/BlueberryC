<?php


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
  

  function overview( $sketches ){
    
    // extract 
    $overview = prepareSketchOverview( $sketches );
    
    
    foreach( $overview as $info ){
      
      echo '<div id="sketch_item">';
      echo '<span id="caption"><a href="'.linkToMe("project").'&sketch='.$info["sketch"].'">'.$info["caption"]."</a></span> (".$info["count"]." revs) ";
      echo timeDiffToString( time() - $info["timestamp"] )." ";
      echo 'by '.implode(",", $info["contributors"])."<br>";
      echo '<span id="description">'.$info["description"]."</span>";
      echo '</div>';

    }

    return $overview;
  }  
    


  function renderAllChilds( $sketch ){
    
    echo "<style>";
    echo '  #history-container {
              padding-left:10px;
              font-size:small;
            }

            #history-sketch {
              border:thin solid blue;
              padding:5px;
              margin-bottom:5px;
            }

            #history-sketch-newest {
              border: thin solid blue;
              background-color: #f0fff0;
              padding:5px;
              margin-bottom:5px;
            }
            ';
    echo "</style>";
    
    echo '<div id="history-container">';
    
      if (empty($sketch->children)){
        echo '<div id="history-sketch-newest">';        
      } else {
        echo '<div id="history-sketch">';       
      }
      echo '<a href="'.linkToMe("download").'&sketch='.$sketch->sketch.'&md5sum='.$sketch->md5sum.'">'.$sketch->caption."</a> ";
      echo $sketch->md5sum." ";
      echo timeDiffToString( time() - $sketch->timestamp)."<br>";
      echo $sketch->description."<br>";
      echo implode(",", $sketch->contributors);
      echo '</div>';
      
      foreach ($sketch->children as $child){
        renderAllChilds( $child );
      }
      
    echo '</div>';
        
  }
  
  function renderSingleProject( $projectSketches ){
  
    foreach ($projectSketches as $sketch){
      renderAllChilds( $sketch );
    }

  }
  
    


?>
