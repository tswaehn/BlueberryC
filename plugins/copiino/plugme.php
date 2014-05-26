<?php

  setCaption( "CoPiino" );
  
  setDefaultPage( PLUGIN_DIR.'sketchbrowser/index.php' );
  
  addMenuItem( 'my::sketches', PLUGIN_DIR.'sketchbrowser/index.php');
  addMenuItem( 'dl::sketches', PLUGIN_DIR.'sync/download.php');
  addMenuItem( 'flashprogrammer', PLUGIN_DIR.'flashprogrammer/prog.php');
  addMenuItem( 'avr controls', PLUGIN_DIR.'avrcontrols/index.php');
  addMenuItem( 'settings', PLUGIN_DIR.'config/index.php' );
  
?> 
