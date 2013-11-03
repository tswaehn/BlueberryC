 
<?php

  if (!isset($url_action)){
    $action='';
  } else {
    $action=$url_action;
  }

  switch ($action){
   case 'start': include( './dummyProcess/dummyProcess.php'); break;   
   case 'done': include( './dummyProcess/done.php'); break;
    default:
	      include( './dummyProcess/selectAction.php' );
  
  }
  
  
?>


 
