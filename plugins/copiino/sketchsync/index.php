<?php


  include('xSketchSync.php');
  
  $sketchSync = new SketchSync();
  
  $files=array('Elephant 128x128.png', 'xArduinoPunkConsole_WiringDiagram_Rev0_Large.gif' );

  
  $args=array();
  $args['login']='test';
  
  $sketchSync->sendFiles( $files, $args );






?>
