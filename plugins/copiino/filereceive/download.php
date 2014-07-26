<?php

  include("serverconfig.php");
  include("./lib/xIniTools.php");
  include("./lib/diverse.php");
  
  include("./download/xSketch.php");
  include("./download/xSketchDownload.php");
  
  include('./lib/jsonSettings.php');
  include('./accounts/xAccounts.php');
  
  
  date_default_timezone_set('Europe/London');

  $sketch = getUrlParam( "sketch" );
  $action = getUrlParam( "action" );
  
  
  $sketchDownload = new SketchDownload();
  
  // check account
  $user= getUrlParam( "user" );
  $email= getUrlParam( "email" );
  $pwd= getUrlParam( "pwd" );
  $scope= getUrlParam( "scope" );
  
  $accounts = new Accounts();
  if ($accounts->check( $user, $email, $pwd ) != 1){
        jsonCurlReturn( array("account"=>"invalid" ));
        exit;
  }

  switch ($action){
    
    case 'overview':      
          
        // return all available projects
        $sketches = $sketchDownload->returnProjectsOverview( $scope, $user, "" );
        jsonCurlReturn( $sketches );
        break;
    
    case 'project':
    
        // return specific projects only
        $sketch = getUrlParam( "sketch" );
        $sketches = $sketchDownload->returnProjectsOverview( $scope, $user, $sketch );
        jsonCurlReturn( $sketches );
    
        break;

    case 'sketch':
    
        // return specific projects only
        $sketch = getUrlParam( "sketch" );
        $user = getUrlParam("scope" );
        $md5sum= getUrlParam("md5sum" );
        $data = $sketchDownload->returnSketch( $user, $sketch, $md5sum );
        jsonCurlReturn( $data );
    
        break;
        
        
        
    default:
        
  }
  
  

?>

