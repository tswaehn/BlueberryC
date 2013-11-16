<?php
  
  $action=getAction();
  
  switch( $action ){
    case 'view': include('./sketchbrowser/view.php');break;
    case 'edit': include('./sketchbrowser/edit.php');break;
    case 'compile': include('./sketchbrowser/compile.php');break;
    
  
  
    default:
	    include('./sketchbrowser/browse.php');
  
  }



?>