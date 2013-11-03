
<?php

  switch ($action){
   case 'start_avr': include( './avrcontrols/startAVR.php'); break;
   case 'stop_avr': include( './avrcontrols/stopAVR.php'); break;
   
    default:
	      include( './avrcontrols/selectAction.php' );
  
  }
  
  
?>

