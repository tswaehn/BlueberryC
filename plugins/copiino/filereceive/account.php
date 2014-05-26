<?php
    
    // input for check account
    //$args = array( "action"=>"check_account", "user"=>$user, "email"=>$email );



  include('./lib/diverse.php');
  include('./lib/jsonSettings.php');
  include('./accounts/xAccounts.php');

  date_default_timezone_set('Europe/London');
  
  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );
  
 
  $action = getUrlParam("action");
  
  if (empty($action)){
    die();
  }
  
  switch ($action){
    
    case 'check_account':
            
            $user=getUrlParam("user");
            $email=getUrlParam("email");
            $pwd=getUrlParam("pwd");
            
            $accounts=new Accounts();
            
            if ($accounts->check( $user, $email, $pwd )){
              jsonCurlReturn( array( "account" => "valid" ) );
            } else {
              jsonCurlReturn( array( "account" => "invalid" ) );
            }
    
            break;
  
  
    case 'register_account':
            
            $user=getUrlParam("user");
            $email=getUrlParam("email");
            $pwd=getUrlParam("pwd");
            
            $accounts=new Accounts();
            
            switch ($accounts->register( $user, $email, $pwd )){
            
              case 1: jsonCurlReturn( array( "account" => "valid" ) );
                      break;
                      
              case -1:jsonCurlReturn( array( "account" => "already exists" ) );
                      break;
                      
              case -2:jsonCurlReturn( array( "account" => "invalid password" ) );
                      break;
                      
              default:
                    jsonCurlReturn( array( "account" => "invalid" ) );
            }
    
            break;
  
  
    default:
            die();
  
  }





?>
