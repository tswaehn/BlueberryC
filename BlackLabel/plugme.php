<?php

  setCaption( "BlackLabelBoard" );
  
  setDefaultPage('index.php');
  
  addMenuItem( 'my::sketches','./sketchbrowser/index.php');
  addMenuItem( 'dl::skteches','./sketcheditor/sketch.php');
  addMenuItem( 'flashprogrammer', './flashprogrammer/prog.php');
  addMenuItem( 'avr controls', './avrcontrols/index.php');

?> 
