<?php 
 
 $action = getUrlParam('action');
 
 switch ($action){
    case 'select': include( PLUGIN_DIR.'flashprogrammer/selectFile.php');break;
    case 'burn': include( PLUGIN_DIR.'flashprogrammer/burn.php');break;
    
    case 'read': 	include( PLUGIN_DIR.'flashprogrammer/read.php');break;
    case 'read_name':	include( PLUGIN_DIR.'flashprogrammer/readName.php');break;
    case 'read_move':	include( PLUGIN_DIR.'flashprogrammer/readMove.php');break;
    
    case 'filesStorage': include( PLUGIN_DIR.'flashprogrammer/filesStorage.php'); break;
    case 'filesStorage_load': include( PLUGIN_DIR.'flashprogrammer/filesStorageLoad.php');break;
    case 'filesStorage_del': include( PLUGIN_DIR.'flashprogrammer/filesStorageDel.php');break;
  
    case 'trash':  include( PLUGIN_DIR.'flashprogrammer/trash.php'); break;
    case 'trash_restore': include( PLUGIN_DIR.'flashprogrammer/trashRestore.php'); break;
    
    default:
	      include( PLUGIN_DIR.'flashprogrammer/selectAction.php' );
  
  }
  
  ?>
