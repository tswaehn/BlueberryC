
  
<?php

  if (!isset($url_action)){
    $action='';
  } else {
    $action=$url_action;
  }

  switch ($action){
   case 'dummy': include( './scriptmaster/dummyProcess.php'); break;   
   case 'done': include( './scriptmaster/done.php'); break;
    default:
	      include( './scriptmaster/selectAction.php' );
  
  }
  
  
?>



