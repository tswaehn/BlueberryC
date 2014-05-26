<?php

  include('serverconfig.php');
  include('./lib/diverse.php');
  include('./lib/xIniTools.php');
  include('./lib/jsonSettings.php');
  
  include('./upload/xSketchUpload.php');
  include('./accounts/xAccounts.php');

  date_default_timezone_set('Europe/London');
  
  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );
  
  
  $returnData=array();
  
  $upload = new SketchUpload();
  
  $errorCode=$upload->check();
  if ($errorCode){
    jsonCurlReturn( array( "error"=> $errorCode, "msg"=> $errorStr[$errorCode], "data" => $returnData ));
    
    die();
  } 
  
  $errorCode= $upload->importFiles();
  if ( $errorCode == 0){
    jsonCurlReturn( array( "error"=> $errorCode, "msg"=> $errorStr[$errorCode], "data" => $returnData ));
  } else {
    jsonCurlReturn( array( "error"=> $errorCode, "msg"=> $errorStr[$errorCode], "data" => $returnData ));
  }
  

?>



