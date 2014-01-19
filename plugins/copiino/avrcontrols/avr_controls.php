<?php

  function startAVR(){
  
  
    echo 'starting AVR ...<br>';

    $output= shell_exec('sudo '.PLUGIN_DIR.'avrcontrols/startAVR.sh');  
    echo "<pre>".$output."</pre>";
  }
  
  function stopAVR(){
    echo 'stopping AVR ...<br>';

    $output= shell_exec('sudo '.PLUGIN_DIR.'avrcontrols/stopAVR.sh');  
    echo "<pre>".$output."</pre>";
  
  }
  
  function enableRS232(){
    
    $output= shell_exec('sudo /usr/share/arduino/hardware/tools/enable-rs232.sh');  
    echo "<pre>".$output."</pre>";
  
  }

  function disableRS232(){
    
    $output= shell_exec('sudo /usr/share/arduino/hardware/tools/disable-rs232.sh');  
    echo "<pre>".$output."</pre>";
  
  }
  
  
?>
 
