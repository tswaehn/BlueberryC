
<?php

  if (!isset($url_action)){
    $action='';
  } else {
    $action=$url_action;
  }

  switch ($action){
    case 'select': include('./flashprogrammer/selectFile.php');break;
    case 'burn': include( './flashprogrammer/burn.php');break;
    
    case 'read': 	include( './flashprogrammer/read.php');break;
    case 'read_name':	include( './flashprogrammer/readName.php');break;
    case 'read_move':	include( './flashprogrammer/readMove.php');break;
    
    case 'filesStorage': include( './flashprogrammer/filesStorage.php'); break;
    case 'filesStorage_load': include( './flashprogrammer/filesStorageLoad.php');break;
    case 'filesStorage_del': include('./flashprogrammer/filesStorageDel.php');break;
  
    case 'trash':  include( './flashprogrammer/trash.php'); break;
    case 'trash_restore': include( './flashprogrammer/trashRestore.php'); break;
    
    default:
	      include( './flashprogrammer/selectAction.php' );
  
  }
  
  
?>

