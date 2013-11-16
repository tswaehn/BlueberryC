
<?php


  $action = getAction();
  
  switch ($action){

   case 'reboot_raspi': include( 'rebootRaspi.php'); break;
   case 'shutdown_raspi': include( 'shutdownRaspi.php'); break;

   case 'dmesg': include( 'dmesg.php'); break;   
   case 'messages': include( 'messages.php'); break;   
   
    default:
	      include( 'selectAction.php' );
  
  }
  
  
?>

