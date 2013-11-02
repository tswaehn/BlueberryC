
<?php

  if (!isset($url_controlstate)){
    $controlstate='';
  } else {
    $controlstate=$url_controlstate;
  }

  switch ($controlstate){
   case 'start_avr': include( './controls/startAVR.php'); break;
   case 'stop_avr': include( './controls/stopAVR.php'); break;

   case 'reboot_raspi': include( './controls/rebootRaspi.php'); break;
   case 'shutdown_raspi': include( './controls/shutdownRaspi.php'); break;

   case 'dmesg': include( './controls/dmesg.php'); break;   
   case 'messages': include( './controls/messages.php'); break;   
   
    default:
	      include( './controls/selectAction.php' );
  
  }
  
  
?>

