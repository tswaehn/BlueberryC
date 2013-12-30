<?php 
 
 $action = getUrlParam('action');
 
 switch ($action){
    
    case 'install': include( PLUGIN_DIR.'install.php');
		    break;
    case 'ask_install': include( PLUGIN_DIR.'ask_install.php');
		    break;
    
    default:
	      include( PLUGIN_DIR.'about.php' );
  
  }
  
  ?>

 