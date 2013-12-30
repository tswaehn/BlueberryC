<?php

  setCaption( "CoPiino" );
  
  setDefaultPage( PLUGIN_DIR.'index.php');
  
  addMenuItem( 'my::sketches', PLUGIN_DIR.'sketchbrowser/index.php');
  addMenuItem( 'dl::sketches', PLUGIN_DIR.'sketcheditor/sketch.php');
  addMenuItem( 'flashprogrammer', PLUGIN_DIR.'flashprogrammer/prog.php');
  addMenuItem( 'avr controls', PLUGIN_DIR.'avrcontrols/index.php');

?> 
