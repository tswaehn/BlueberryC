
<?php

  include( PLUGIN_DIR.'avrcontrols/avr_controls.php' );
  
  $action = getUrlParam('action');
 
  switch ($action){
    case 'start_avr': startAVR(); break;
    case 'stop_avr': stopAVR();	break;

    case 'enable_rs232': enableRS232();	break;
    case 'disable_rs232': disableRS232();	break;
    
    default:

    
  }
    
?>

<h3>AVR Tools</h3>


<div id="controls_item">
  <form action="<?php postToMe(); ?>" method="post">
  <input type="hidden" name="action" value="start_avr">
  <input type="submit" name="submit" value="Start AVR">
  </form>

  starts the onboard AVR
</div>

<div id="controls_item">
  <form action="<?php postToMe(); ?>" method="post">
  <input type="hidden" name="action" value="stop_avr">
  <input type="submit" name="submit" value="Stop AVR">
  </form>
  stops the onboard AVR
</div>

<div id="controls_item">
  <form action="<?php postToMe(); ?>" method="post">
  <input type="hidden" name="action" value="enable_rs232">
  <input type="submit" name="submit" value="Enable RS232">
  </form>
  enables the levelshifter which connects the raspi to copiino
</div>

<div id="controls_item">
  <form action="<?php postToMe(); ?>" method="post">
  <input type="hidden" name="action" value="disable_rs232">
  <input type="submit" name="submit" value="Disable RS232">
  </form>
  disables the levelshifter which connects the raspi to copiino
</div>

