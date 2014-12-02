#!/usr/bin/php
<?php

  date_default_timezone_set('Europe/London');


  include('plugins.php');

  // Set time limit to indefinite execution
  set_time_limit (0);

  // Set the ip and port we will listen on
  //$address = '127.0.0.1';
  $address = '0.0.0.0';
  $port = 9000;
  $max_clients = 10;

  // Array that will hold client information
  $clients = Array();
  for ($i=0;$i<$max_clients;$i++){
    $clients[$i]['sock']=null;
  }
  
  $read=array();
  
  // Create a TCP Stream socket
  $socket = socket_create(AF_INET, SOCK_STREAM, 0);
  
  if ($socket === false) {
      $errorcode = socket_last_error();
      $errormsg = socket_strerror($errorcode);
      
      die("Couldn't create socket: [$errorcode] $errormsg");
  }
  
  // Bind the socket to an address/port
  @socket_bind($socket, $address, $port) or die('Could not bind to address '.$address.':'.$port."\n");

  // Start listening for connections
  socket_listen($socket);

  
  // Loop continuously
  echo "ready for connections\n";
  
  while (true) {

    // Setup clients listen socket for reading
    $read[0] = $socket;

    for ($i = 0; $i < $max_clients; $i++) {

	  if ($clients[$i]['sock']  != null){
	      $id = $clients[$i]['sock'] ;
	      echo 'read from '.$id."\n";
	      $read[$i + 1] = $id;
	  }

    }

    // Set up a blocking call to socket_select()
    $write=null;
    $except=null;
    
    $num_changed_sockets = socket_select($read,$write,$except, $sek=5);
    if ($num_changed_sockets == false){
      echo "nothing to do, ... \n";
      $masterTask->idleAll();
      continue;
    }
    
    // 
    $masterTask->idleAll();
    
    if ($num_changed_sockets > 0){
      $new_socket= socket_accept( $socket );
      
      $i=0;
      $socket_error=0;
      $data = "";
      $starttime=time();
      
      do {
	$package = socket_read( $new_socket, 512, PHP_BINARY_READ );
	$socket_error = socket_last_error($new_socket);
	
	echo "recived package ".$i++.">".$package."\n";
	$data .= $package;
	$msg_start=strpos( $data, "<CONTENT>" );
	$msg_end=strpos( $data, "</CONTENT>" );

	if (($socket_error == 0) && (empty($package))){
	  // create a socket error if nothing usefull received;
	  $socket_error=96;
	  break;
	}
	
	
      } while ( ( $msg_end === false ) && ($socket_error == 0) );
      
      if ($socket_error == 0){
	echo "message complete\n";
	
	// strip content tags
	$data = preg_replace("/<CONTENT>|<\/CONTENT>/", "", $data );

	if ($data == '[{"":{"":"exit-socket"}}]'){
	  break;
	}
	
	// 
	$response = $masterTask->processData( $data );
	
	$response = "<RESPONSE>".$response."</RESPONSE>";
	
	socket_write( $new_socket, $response );
      
      } else {
	// stopped reading with an error
	echo "socket error ".$socket_error."\n";
      }
      
      socket_close( $new_socket );
    
    }


} // end while

  // shutdown plugins
  $masterTask->shutdownAll();

  // Close the master socket
  socket_close($socket);
  
  echo "closed socket forever\n";
  echo "exiting";
?>
 
