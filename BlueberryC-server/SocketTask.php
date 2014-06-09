<?php

  $output="";
  
  class SocketTask {
    
    protected $name;
    protected $tasks;

    protected $times;
    protected $socket;
    
    function __construct( $who ){
      
      $this->name = $who;
      $this->tasks=array();
      $this->times=0;
      
      $this->log( "creating ".$this->name );
	
    }

    // overrided by extended classes
    function interpreter( $action, $str ){
      
      switch( $action ){
      
      
	default:
		  $str = strtoupper( $str );
		  $this->sendData( $str );
	
      }
    }

    // overrided by extended classes
    function setup(){
      $this->log("nothing to do");
    }
    // general:
    function shutdown(){
      $this->log("nothing to do");
    }
    
    function process( $data ){
      // nothing to do
    }
    
    function idle(){
      // nothing to do
    }
    
    
    // general: 
    function sendData( $out ){
      global $output;
    
      $str = "(". $this->name .")".$out."\n";
      $output .= $str;  
    }    
    
    // general:
    function log( $str ){
      echo $this->name."-log>".$str."\n";      
    }
    
    // ======================
    // collection functions
    
    // add task to collection
    function addTask( $task ){
      $this->tasks[] = $task;  
    }

    // setup all tasks (prepare to run)
    function setupAll(){

      foreach ($this->tasks as $task){
	$this->log( "setup ".$task->name );
	$task->setup();
      }
      
      $this->setup();
      
    }
    
    // stop all tasks (clean up)
    function shutdownAll(){
      
      foreach ($this->tasks as $task){
	$this->log( "shutdown ".$task->name );
	$task->shutdown();
      }
      
      $this->shutdown();
    
    }
    
    function processAll( $data ){
    
      foreach($this->tasks as $task){
	$this->log("processing ".$task->name );
	$task->process( $data );
      }
      
      $this->process( $data );
    
    }
    
    function idleAll(){
    
      foreach($this->tasks as $task){
        $this->log("idle ".$task->name );
        $task->idle();
      }
      
      $this->idle();
    
    }
      
    function findPlugin( $name ){

      if (strcmp( $this->name, $name)== 0){
	return $this;
      }
      
      foreach ($this->tasks as $task){
	$sub = $task->findPlugin( $name );
	if ($sub!=""){
	  return $sub;
	}
	
      }
      
      return "";
    }
    
    // -----------------------------------------------
    // process new data
    function processData( $request ){
      global $output;
      
      // decode incoming request
      $obj=json_decode( $request, true );
      
      if (empty( $obj )){
	return "input empty";
      }
      if (!is_array( $obj )){
	return "input is not an array";
      }
      
      foreach ($obj as $task){
      
	foreach ($task as $name=>$data){
	  $plugin = $this->findPlugin( $name );
	  if (!$plugin == "" ){
	  
	    if (!is_array( $data )){
	      $plugin->sendData("wrong format");
	      continue;
	    }
	    
	    foreach ($data as $action=>$str){
	      $plugin->interpreter( $action, $str );
	    }

	  }
	}
      }
      // output return data
      $o=$output;
      $output = "";
      return $o;
    }
    
    
    



  }



?> 
