<?php

  define('MAX_SENSOR_COUNT', 100 );
  
  class CommandInterface {
  
    protected $sensors;
    protected $index;
    
    protected $lastSens;
    
    function __construct(){
      
      $this->sensors=array();
      $this->index=0;
      $this->lastSens=array();
    }
  
  
    function execute( $cmd ){
      
      $ret="";
      if (strlen($cmd) < 3){
	return "";
      }
      
      // convert first char into command code
      $code = ord( $cmd[0] );
      
      
      // now decide what to do depending on the received cmd-code
      if ($code == 208){
        $this->evalTimeStep();
      
      } else if (($code >=200 ) && ($code < 220)){
	$channel=$code-200;
	$ret= $this->evalSensor( $channel, $cmd );
      } else {
      
      }
    
      return $ret;
    } 
    
    // insert current values into the array at each time-step
    function evalTimeStep(){
      $this->index++;
      
      if ($this->index >= MAX_SENSOR_COUNT){
        $this->index = 0;
      }
      
      foreach ($this->lastSens as $channel=>$value){
        $this->sensors[$channel][$this->index]=$value;
      }
      
    }
    
    // set current values
    function evalSensor( $channel, $cmd ){
      $value = (ord($cmd[1])-ord('a'))*16 + (ord($cmd[2])-ord('a'));
      
      // prepare new channel if not yet present
      if (!isset($this->sensors[$channel])){
        for($i=0;$i<=MAX_SENSOR_COUNT;$i++){
          $this->sensors[$channel][$i] = 0;
        }
      }
      
      // add new value
      $this->lastSens[$channel] = $value;
      
      //return "sensor ".$channel." ".$value;
    }

    function getSensorValues(){
      
      // convert into string
      $str="";
      foreach ($this->sensors as $channel=>$values){
	$str .= $channel.":";
	// get last/latest value
	$str .= $this->lastSens[$channel]; 
	$str .= "\n";
      }
    
      return $str;
    }
    
    function getSensorLog(){
      // calc index of first item
      $first = $this->index+1;
      if ($first >= MAX_SENSOR_COUNT){
        $first = 0;
      }
    
      // convert into string
      $str="";
      foreach ($this->sensors as $channel=>$values){
	
	// add channel number
	$str .= $channel.":";
	
	$k=$first;
	
	for ($i=0;$i< MAX_SENSOR_COUNT;$i++){
          if ($k>=MAX_SENSOR_COUNT){
            $k=0;
          }
	  $str .= $this->sensors[$channel][$k++];
	  
	  // add comma seperator
	  if ($i< MAX_SENSOR_COUNT){
            $str .= ",";
          }
	}
	
	// add line break
	$str .= "\n";
      }
    
      return $str;
    }
    
    

  }
  
?>
