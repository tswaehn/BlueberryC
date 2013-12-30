<?php

  // for the app menu
  include( './lib/menuCreator.php');

  define('PLUGINS', './plugins/');
  
  $app=getUrlParam('app');
  $pluginBaseDir = sanitizeDir( PLUGINS."$app/" );

  // strip/replace unwanted characters and check existance
  if (!is_dir( $pluginBaseDir )){
    $app = 'home';  
    $pluginBaseDir = sanitizeDir( PLUGINS."$app/" );    
  } 
  if (!is_file($pluginBaseDir.'/plugme.php')){
    $app = 'home';
    $pluginBaseDir = sanitizeDir( PLUGINS."$app/" );    
  }
  
  
  // setup 
  $APP=array();
  $APP['title'] = $app;
  $APP['caption'] = $app;
  $APP['base_dir'] = $pluginBaseDir;
  $APP['plugme'] = sanitizeDir( $pluginBaseDir."plugme.php" );
  $APP['menu'] = array();
  $APP['default_page'] = sanitizeDir( PLUGINS.'index.php' );
  
  
  define('PLUGIN_DIR', $pluginBaseDir );
    
  // include plugme (and test again - produce major error if anything fails)
  if (!is_file( $APP['plugme'] )){
    echo "<h1>failed to load plugin -".$app."-<h1>";
    die();
  }
  
  include( $APP['plugme'] );
  
?>
