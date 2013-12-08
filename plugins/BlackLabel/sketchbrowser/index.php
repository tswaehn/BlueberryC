<?php
  
  $action=getAction();
  
  switch( $action ){
    case 'view': include('./sketchbrowser/view.php');break;
    case 'edit': include('./sketchbrowser/edit.php');break;
    case 'setup': include('./sketchbrowser/setup.php');break;
    case 'compile': include('./sketchbrowser/compile.php');break;
    case 'browse': include('./sketchbrowser/browse.php');break;
    case 'trash': include('./sketchbrowser/trash.php');break;
  
  
    default:
	    include('./sketchbrowser/browse.php');
  
  }



?>