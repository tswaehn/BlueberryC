<?php

  include('./plugins/rs232/CommandInterface.php');
  
  define('DEFAULT_HIST_LEN', 20 );
  
  class Rs232 extends SocketTask {
    
    protected $fp;
    protected $ci;
    protected $history;
    protected $hist_remain;
    protected $hist_size;
    
    function setup(){
      $this->history= array();
      $this->hist_remain="";
      $this->hist_len=DEFAULT_HIST_LEN;
      
      $this->ci = new CommandInterface();
      
      $this->fp = @fsockopen( 'localhost', 10000, $errno, $errstr, 10 );
      
      if (!$this->fp){
	$this->log( "error no socket" );
	$this->log( $errno. " " .$errstr );

      } else {
	$this->log( "connected" );
      }
    }
    
    function shutdown(){
      $this->log("close rs232 connection");
      if ($this->fp){ 
	fclose( $this->fp );
      }
    
    }
    
    function addHistory( $str ){

      // remove all \r
      $str = preg_replace("/\r/", "", $str);
      // glue the remaining parts toghether with received data
      $str = $this->hist_remain.$str;

      // split by \n
      $cmds = preg_split( "/\n/", $str, -1, PREG_SPLIT_NO_EMPTY );

      // get the cmd count
      $len = count( $cmds );
      $i=0;
      // walk through cmds
      foreach ($cmds as $cmd){
	// skip the last one
      	if ($i <= ($len-1)){
	  // check only (len-1) cmds as the last cmd might be incomplete
	  
	  $ret=$this->ci->execute( $cmd );
	  if ($ret != ""){
	    $cmd .= " (".$ret.")"; 
	  }
	  
	  // add information to history
	  $this->history[] = $cmd;
	  
	} else {
	  $this->hist_remain = $cmd;
	}
      }
      
      // if history is too large, ... cut it
      $len = count( $this->history );
      if ($len >=$this->hist_len){
	$cut_size = $len - $this->hist_len;
	array_splice( $this->history, 0, $cut_size );
      }
     
      
    }
    
    function tx( $text ){


      $this->log("TX ".$text);
      $this->addHistory(  "TX>".$text ) ;
      
      // add end-line
      $text .= "\n";
      
      if (!$this->fp){ 
	$this->log( "no stream available" );
	return;
      }
      
      $len = strlen( $text );
      $count=0;
      stream_set_timeout($this->fp, 5 );
      
      do {

	$res = 	fwrite( $this->fp, substr( $text, $count) );
	
	if (( $res === false ) || ($res == 0) ){
	  $this->log("failed to write");
	  break;
	}
	
	$count += $res;
	
      } while ( $count < $len );
      
      $this->log("done");
      
    }
    
    function rx(){
      $this->log("RX ");

      if ($this->fp){
      
	stream_set_timeout($this->fp, 0, 100 );
	$info = stream_get_meta_data($this->fp);
	
	while ((!feof($this->fp)) && (!$info['timed_out'])) {
	  $info = stream_get_meta_data($this->fp);
	  $disp = fread( $this->fp, 1024 );

	  if ($disp != false){
	    $this->addHistory( $disp );
	    $this->log( $disp );
	  }
	}
      }

      $this->log("done");
    }
    
    function update( $history_len ){

      // check value
      if ($history_len==""){
	$history_len=0;
      }
	
      // set new len
      if ($history_len > 0){
	$this->log("new hist len ".$history_len );
	$this->hist_len=$history_len;
      }
      
      // return data
      foreach ($this->history as $line){
	$this->sendData( $line );
      }
    
    }
    
    function getSensorValues(){
    
      $str=$this->ci->getSensorValues();
      
      $this->sendData( $str );
      
    }
    
    function getSensorLog(){
    
      $str=$this->ci->getSensorLog();
      
      $this->sendData( $str );
      
    }
        
  
    function interpreter( $action, $str ){
      $this->log("interpreting ".$action);

      // receive data to clear rx buffer
      $this->rx();
      
      // check what to do
      switch($action){
	
	case 'tx': $this->tx($str); break;
	case 'rx': $this->rx(); break;
	case 'update': $this->update( $str ); break;
	case 'sensor': $this->getSensorValues(); break;
	case 'sensorlog': $this->getSensorLog(); break;
	
	default: $this->sendData("wrong format");
      }
    
    }
    
  
  }

  // generate object and add to global list
  $me = new Rs232('rs232');
  $masterTask->addTask( $me );


?>
