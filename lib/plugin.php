<?php

  include( './lib/menuCreator.php');
  
  date_default_timezone_set('Europe/London');

  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );



  if (!isset($url_app)){
    $app='home';
  } else {
    $app=$url_app;
  }

  // strip/replace unwanted characters
  if (!is_dir( $app )){
    $app = 'home';  
  } 
  if (!is_file($app.'/plugme.php')){
    $app = 'home';
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
