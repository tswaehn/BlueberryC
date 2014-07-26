<?php

  
  define("ACCOUNTS_CONF", "./accounts/accounts.conf");
  
  
  class Accounts extends JsonSettings {
    
    private $accounts;
  
    function __construct(){
      
      parent::__construct( ACCOUNTS_CONF );
      
      $this->accounts = $this->getConfig("accounts");
      
      if (empty($this->accounts)){
        $this->accounts=array();
      }
    }
    
    function save(){
      $this->setConfig( "accounts", $this->accounts );
    }


    function get( $user ){
    
      foreach ($this->accounts as $account){
        if (strcmp($user,$account["user"]) == 0 ){
          return $account;
        }
      }

      return 0;
    }

    /*
        \return: 0 .. account invalid
                 1 .. account valid
    */
    function check( $user, $email, $pwd ){
      
      $account = $this->get( $user );
      
      if (empty($account)){
        return 0;
      }
        
      // check mail
      if (strcmp($email, $account["email"]) != 0){
        return 0;
      }
      
      // check pass
      if (strcmp($pwd, $account["pwd"]) != 0){
        return 0;
      }
      
      return 1;
    }

    /*
        \return: 0 .. account invalid
                 1 .. account created
                 -1 .. account already exists
                 -2 .. invalid password
    */
    function register( $user, $email, $pwd ){
      
      // account exists? 
      $account = $this->get($user);
      if (!empty($account)){
        return -1;
      }
      
      // check for empty pass
      if (empty($pwd)){
        return -2;
      }
      
      $account=array();
      $account["user"]=$user;
      $account["email"]=$email;
      $account["pwd"]=$pwd;
      $account["date"]=time();
      
      // add account
      $this->accounts[]= $account;
      $this->save();      
      
      // double check account
      if ($this->check( $user, $email, $pwd )){
        return 1;
      }
      
      return 0;
    }




  }
  
  
?>
