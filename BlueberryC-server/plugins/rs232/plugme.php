<?php
  
  include('./plugins/rs232/CommandInterface.php');
  
  define('DEFAULT_HIST_LEN', 1000 );
  
  class Rs232 extends SocketTask {
    
    protected $fp;
    protected $history;
    protected $hist_remain;
    protected $hist_size;
    
    protected $fpLogfile; // file pointer
    
    function setup(){
      $this->history= array();
      $this->hist_remain="";
      $this->hist_len=DEFAULT_HIST_LEN;
      
      
      $this->fp = @fsockopen( 'localhost', 10000, $errno, $errstr, 10 );
      //$this->fp = @fsockopen( '192.168.5.130', 10000, $errno, $errstr, 10 );
      
      if (!$this->fp){
	$this->log( "error no socket" );
	$this->log( $errno. " " .$errstr );

      } else {
	$this->log( "connected" );
      }
      
      
     $this->startLogging( "/usr/share/BlueberryC/plugins/rs232/logs/");
    }
    
    function idle(){
      // when in idle, ... just read new data
      $this->rx();
    }
    
    function timestamp(){
      $date=date( "Y-m-d H_i_s" );
      return $date;
    }
    
    function shutdown(){
      $this->log("close rs232 connection");
      $this->logToFile( "stop logging rs232" );
      if ($this->fp){ 
	fclose( $this->fp );
      }
    
    }
    
    function startLogging( $folder ){
      if (!file_exists( $folder )){
        mkdir( $folder, 0777, true );
        if (!file_exists( $folder )){
          return null;
        }
      }
      
      $filename = $folder.$this->timestamp()."-rs232.log";
      
       $this->fpLogfile = fopen( $filename, "a" );

      $this->logToFile( "start logging rs232" );
    }
    
    function logToFile( $str ){

      // leave if no file is open
      if ($this->fpLogfile == null){
        return;
      }
      // add timestamp "new-line"
      $str = "[".$this->timestamp()."] ". $str. "\n";
      
      // put to file
      fwrite( $this->fpLogfile, $str );
    }
    
    function addHistory( $str, $isRx ){

      // remove all \r
      $str = preg_replace("/\r/", "", $str);

      
      if (!$isRx){
        // split by \n
        $cmds = preg_split( "/\n/", $str );
        foreach ($cmds as $cmd){
          $cmd = "TX>". $cmd;
          
          $this->logToFile( $cmd );
          // add information to history
          $this->history[] = $cmd;
        }
      }
      
      if ($isRx){
      
        // glue the remaining parts toghether with received data
        $str = $this->hist_remain.$str;
        $this->hist_remain= "";

        // split by \n
        $cmds = preg_split( "/\n/", $str );
        
        // get the last one;
        $this->hist_remain= array_pop( $cmds );
        
        // walk through cmds
        foreach ($cmds as $cmd){

            // add information to history
            $this->history[] = "RX>".$cmd;
            $this->logToFile("RX>".$cmd );
            
          
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

    
      $this->addHistory( $text, false ) ;
      
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
      
      
    }
    
    function rx(){

      
      if ($this->fp){
      
	stream_set_timeout($this->fp, 0, 50 );
	$info = stream_get_meta_data($this->fp);
	
	while ((!feof($this->fp)) && (!$info['timed_out'])) {
	  $info = stream_get_meta_data($this->fp);
	  $disp = fread( $this->fp, 1024 );

	  if ($disp != false){
            $this->addHistory( $disp, true );
	  }
	}
      }
    }
    
    function update( $history_len=DEFAULT_HIST_LEN ){

      // check value
      if ($history_len<= 0){
	$history_len=DEFAULT_HIST_LEN;
      }
	

      $count= count($this->history);
      $start= $count-$history_len;
      if ($start<0){
        $start= 0;
      }
      
      // return data
      for ($i=$start; $i<$count; $i++){
        $line= $this->history[$i];
	$this->sendData( $line );
      }
    
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
	
	default: $this->sendData("wrong format");
      }
    
    }
    
  
  }

  // generate object and add to global list
  $me = new Rs232('rs232');
  $masterTask->addTask( $me );


?>
