 
<?php

/*
  if (!isset($url_action)){
    $action='';
  } else {
    $action=$url_action;
  }
*/  
  $action=getUrlParam("action");
  

  switch ($action){
   case 'start': include( PLUGIN_DIR.'dummyProcess/dummyProcess.php'); break;   
   case 'done': include( PLUGIN_DIR.'dummyProcess/done.php'); break;
    default:
	      include( PLUGIN_DIR.'dummyProcess/selectAction.php' );
  
  }
  
  
?>


 
