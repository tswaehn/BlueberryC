<?php


  

  function sortByTime( $a, $b ){
    if ($a->timestamp>$b->timestamp){
      return +1;
    }
    if ($a->timestamp<$b->timestamp){
      return -1;
    }
    return 0;  
  }

  function renderAllChildren( $sketch, $scope ){
    
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
        
        // sort children
        uasort( $sketch->children, "sortByTime" );
      }
      echo '<a href="'.linkToMe("show_single").'&user='.$sketch->user.'&sketch='.$sketch->sketch.'&md5sum='.$sketch->md5sum.'&scope='.$scope.'">'.$sketch->caption."</a> ";
      echo '(rev '.$sketch->revision.' by '.$sketch->user.') ';
      echo timeDiffToString( time() - $sketch->timestamp)." ";
      echo '['.$sketch->md5sum."]<br>";
      if (empty($sketch->commitMsg)){
        echo "no commit message available<br>";
      } else {
        echo $sketch->commitType.'>'.$sketch->commitMsg."<br>";
      }
      echo '</div>';
      
      foreach ($sketch->children as $child){
        renderAllChildren( $child, $scope );
      }
      
    echo '</div>';
        
  }
  
  
  function renderSingleProject( $projectSketches, $scope ){
    
    // foreach flat sketch without history
    foreach ($projectSketches as $sketch){
      // display all its children
      renderAllChildren( $sketch, $scope );
    }

  }
    












?>
 
