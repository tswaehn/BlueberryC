<?php
    
  include('./sketchbrowser/xBrowser.php');
  
  $sketch = getUrlParam('sketch');
  $do = getUrlParam('do');
  
  $browser = new Browser();


  // execute pre actions like save
  switch ($do){
    
    case 'save_caption': $browser->saveSketchCaption($sketch); break;
    case 'save_thumbnail': $browser->saveSketchThumbnail($sketch); break;
    case 'save_description': $browser->saveSketchDescription($sketch); break;
    case 'save_wiring': $browser->saveSketchWiring($sketch); break;

  
  }
  
  // display setup
  $browser->setupSketch( $sketch );
?>



 
