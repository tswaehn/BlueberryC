<?php
  
  $action=getAction();
  
  switch( $action ){
    case 'view': include( PLUGIN_DIR.'sketchbrowser/view.php');break;
    case 'edit': include( PLUGIN_DIR.'sketchbrowser/edit.php');break;
    case 'setup': include( PLUGIN_DIR.'sketchbrowser/setup.php');break;
    case 'compile': include( PLUGIN_DIR.'sketchbrowser/compile.php');break;
    case 'browse': include( PLUGIN_DIR.'sketchbrowser/browse.php');break;
    case 'trash': include( PLUGIN_DIR.'sketchbrowser/trash.php');break;
  
  
    default:
	    include( PLUGIN_DIR.'sketchbrowser/browse.php');
  
  }



?>