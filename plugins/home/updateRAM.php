<?php
  
  function updateRAM(){
    
    exec( 'free -m', $output, $retVal);

    $line1 = $output[1];
    $values1  = preg_split("/ /", $line1, -1, PREG_SPLIT_NO_EMPTY);
    $totalRAM = $values1[1];
    
    $line2 = $output[2];
    $values2  = preg_split("/ /", $line2, -1, PREG_SPLIT_NO_EMPTY);
    
    $usedRAM = $values2[2];
    $freeRAM = $values2[3];
    
    echo "total ".$totalRAM."MB<br>";
    echo "used ".$usedRAM."MB<br>";
    echo "free ".$freeRAM."MB<br>";
  
  }
    
  //updateRAM();
  
  //exec( 'top -b -n2 -d0.5 | grep -E "top .* up|Cpu\(s\)|Mem|Swap"', $output, $retVal);
  exec( 'top -b -n2 -d0.5', $output, $retVal);
  
  $lines = preg_grep( "/top .* load|Tasks:|Cpu\(s\):|Mem:|Swap:/", $output );
  
  $ord = array();
  
    foreach ($lines as $line ){
      
      $values = preg_split("/,| /", $line, -1, PREG_SPLIT_NO_EMPTY);
      $stats[] = $values;
      //echo implode(" ",$values)."<br>";
    
    }  
    
    //
    $time= $stats[5][2];
    $uptime = $stats[5][4];
    $users = $stats[5][5];
    //
    $cpu = $stats[7][1];
    $cpuAvg = $stats[2][1];
    //
    $totalRAM = $stats[8][1];
    $usedRAM = $stats[8][3];
    $freeRAM = $stats[8][5];
    
    echo "current time ".$time." uptime ".$uptime." with ".$users." users connected<br>";
    echo "cpu ".$cpu." (average ".$cpuAvg.")<br>";
    echo "RAM total ".$totalRAM." used ".$usedRAM." free ".$freeRAM."<br>";
    //
    
  /*  
     
    echo "<p>";
  $output = "";
  exec( 'cat /proc/stat | grep cpu', $output, $retVal);
    
    $line = $output[0];
    
    $values = preg_split("/ /", $line, -1, PREG_SPLIT_NO_EMPTY);
    
    $user = $values[1];
    $nice = $values[2];
    $system = $values[3];
    $idle = $values[4];
    $wait = $values[5];
    
    $load = ($user + $system) / ($user+$system+$idle)* 100;
    
    echo "load is :".$load."%<br>";
    
    foreach ($output as $line ){
      
      echo $line."<br>";
    
    }
    */
    /*
  if (is_array($output)){
    foreach ($output as $line ){
      
      echo $line."<br>";
    
    }
    $title = $output[0];
    $header = $output[1];
    $values = $output[2];
    
    $header = preg_split("/ /", $header, -1, PREG_SPLIT_NO_EMPTY);
    $values = preg_split("/ /", $values, -1, PREG_SPLIT_NO_EMPTY);
    
    foreach ($header as $key=>$item){
      echo $item." ".$values[$key]."<br>";
    }
    
  }

*/

?>
