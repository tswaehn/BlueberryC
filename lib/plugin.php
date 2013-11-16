<?php

  include( './lib/menuCreator.php');
 
  function getAction(){
      return getGlobal('action');
  }
  
  
  $app=getUrlParam('app');

  // strip/replace unwanted characters and check existance
  if (!is_dir( $app )){
    $app = 'missing';  
  } 
  if (!is_file($app.'/plugme.php')){
    $app = 'missing';
  }
  
  
  // setup 
  $APP=array();
  $APP['title'] = $app;
  $APP['caption'] = $app;
  $APP['base_dir'] = "./$app/";
  $APP['plugme'] = $APP['base_dir']."plugme.php";
  $APP['menu'] = array();
  $APP['default_page'] = 'index.php';
  
  
  // include plugme
  include( $APP['plugme'] );
  
?>
