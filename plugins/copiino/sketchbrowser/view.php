<?php
    
  include( PLUGIN_DIR.'sketchbrowser/xBrowser.php');
  
  $sketch = getUrlParam('sketch');
  
  $browser = new Browser();

  $browser->renderSketch( $sketch );
?>



