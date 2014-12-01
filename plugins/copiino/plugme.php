<?php

  setCaption( "CoPiino" );
  
  setDefaultPage( PLUGIN_DIR.'sketchbrowser/index.php' );
  
  addMenuItem( 'my::sketches', PLUGIN_DIR.'sketchbrowser/index.php');
  
  if (defined("DEBUG")){
    addMenuItem( 'sketch::cloud', PLUGIN_DIR.'sync/download.php');
  }
  
  addMenuItem( 'flashprogrammer', PLUGIN_DIR.'flashprogrammer/prog.php');
  addMenuItem( 'avr controls', PLUGIN_DIR.'avrcontrols/index.php');
  
  if (defined("DEBUG")){
    addMenuItem( 'settings', PLUGIN_DIR.'config/index.php' );
  }
  
?> 
