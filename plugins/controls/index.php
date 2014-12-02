
<?php


  $action = getAction();
  
  switch ($action){

   case 'reboot_raspi': include( PLUGIN_DIR.'rebootRaspi.php'); break;
   case 'shutdown_raspi': include( PLUGIN_DIR.'shutdownRaspi.php'); break;

   case 'dmesg': include( PLUGIN_DIR.'dmesg.php'); break;   
   case 'messages': include( PLUGIN_DIR.'messages.php'); break;   
   case 'syslog': include( PLUGIN_DIR.'syslog.php'); break;
   
    default:
	      include( PLUGIN_DIR.'selectAction.php' );
  
  }
  
  
?>

