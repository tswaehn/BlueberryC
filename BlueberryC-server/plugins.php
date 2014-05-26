<?php

  include('SocketTask.php');
  
  $masterTask = new SocketTask('master');
  
  // todo: automatic include
  define('PLUGINDIR', './plugins/');
  
  include( PLUGINDIR.'rs232/plugme.php');

  
  // now setup tasks
  //print_r( $masterTask );
  
  
  $masterTask->setupAll();
  
?> 
