
<?php
  
  $action = getAction();
  
  switch ($action){
   case 'start_avr': include( PLUGIN_DIR.'avrcontrols/startAVR.php'); break;
   case 'stop_avr': include( PLUGIN_DIR.'avrcontrols/stopAVR.php'); break;
   
    default:
	      include( PLUGIN_DIR.'avrcontrols/selectAction.php' );
  
  }
  
  
?>

