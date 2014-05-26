<?php

  define('MAX_SENSOR_COUNT', 100 );
  
  class CommandInterface {
  
    protected $sensors;
    
    function __construct(){
      
      $this->sensors=array();
    
    
    }
  
  
    function execute( $cmd ){
      
      $ret="";
      if (strlen($cmd) < 3){
	return "";
      }
      
      // convert 1st char into command code
      $code = ord( $cmd[0] );
      
      
      if (($code >=200 ) && ($code < 220)){
	$channel=$code-200;
	$ret= $this->evalSensor( $channel, $cmd );
      } else {
      
      }
    
      return $ret;
    } 

    function evalSensor( $channel, $cmd ){
      $value = (ord($cmd[1])-ord('a'))*16 + (ord($cmd[2])-ord('a'));
      $this->sensors[$channel][] = $value;

      // limit array size by cutting oldest items
      $len = count( $this->sensors[$channel] );
      if ($len >=MAX_SENSOR_COUNT){
	$cut_size = $len - MAX_SENSOR_COUNT;
	array_splice( $this->sensors[$channel], 0, $cut_size );
      }
      
      return "sensor ".$channel." ".$value;
    }

    function getSensorValues(){
      // convert into string
      $str="";
      foreach ($this->sensors as $channel=>$values){
	$str .= $channel.":";
	// get last/latest value
	$str .= end($values); 
	$str .= "\n";
      }
    
      return $str;
    }
    
    function getSensorLog(){
      // convert into string
      $str="";
      foreach ($this->sensors as $channel=>$values){
	$str .= $channel.":";
	foreach ($values as $value){
	  $str .= $value.",";
	}
	$str .= "\n";
      }
    
      return $str;
    }
    
    

  }
  
?>
