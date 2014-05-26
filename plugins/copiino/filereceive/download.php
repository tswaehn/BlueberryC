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
  
  switch ($action){
    
    case 'overview':      
          
        // check account
        $user= getUrlParam( "user" );
        $email= getUrlParam( "email" );
        $pwd= getUrlParam( "pwd" );
        
        $accounts = new Accounts();
        if ($accounts->check( $user, $email, $pwd ) != 1){
              jsonCurlReturn( array("account"=>"invalid" ));
        }

        // return all available projects
        $sketchDownload->returnProjectsOverview();
        break;
    
    case 'project':
    
        // check account
        $user= getUrlParam( "user" );
        $email= getUrlParam( "email" );
        $pwd= getUrlParam( "pwd" );
        
        $accounts = new Accounts();
        if ($accounts->check( $user, $email, $pwd ) != 1){
              jsonCurlReturn( array("account"=>"invalid" ));
        }

    
        // return specific projects only
        $sketch = getUrlParam( "sketch" );
        $sketchDownload->returnProjectsOverview( $sketch );
        break;
    
        break;
        
    default:
        
  }
  
  

?>

