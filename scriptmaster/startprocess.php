
<?php

function start( $cmd, $page_, $action_ ){
  
  $GLOBALS["page"]=$page_;
  $GLOBALS["action"]=$action_;
  

  exec('./scriptmaster/startprocess.sh '.$cmd, $output, $retVal);  

  foreach ($output as $line){
    echo $line."<br>";
  }
  
  switch ($retVal){
    case 0: echo 'starting script ...<br>';break;
    case 1: echo "script allready exececuting ...<br>";break;
    case 127: echo "cannot run script<br>";break;
    default:
	echo "return ".$retVal."<br>";
  }

  
  
  include("./scriptmaster/ajax.php");

  
  
}

?>
