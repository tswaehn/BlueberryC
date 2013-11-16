<?php
    
  include('./sketchbrowser/xBrowser.php');
  
  $sketch = getUrlParam('sketch');
  
  $browser = new Browser();

  $caption = $sketch;
  echo '<h3>View Sketch - '.$caption.'</h3>';

  $browser->renderSketch( $sketch );
?>



