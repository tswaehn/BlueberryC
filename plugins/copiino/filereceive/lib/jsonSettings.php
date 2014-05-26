<?php


  class JsonSettings {
    
    public $settings;
    protected $fileName;
    
    function __construct( $configFileName ){
      $this->fileName = $configFileName;
      $this->readFromFile();
    }
    
    function setDefault(){
      $this->settings = array();
    }
  
    function readFromFile(){
      
      $settings = "";
      
      if (is_file(  $this->fileName )){
        $contents = file_get_contents( $this->fileName );
        
        $settings = json_decode( $contents, TRUE );
      }
      
      if (empty($settings)){
        $this->setDefault();
        $this->writeToFile();
      }
    
      $this->settings=$settings;
    
    } 
    
    function writeToFile(){
      
      $settings = $this->settings;
      
      $contents = json_encode( $settings );
      
      file_put_contents( $this->fileName, $contents );
      
    }
   
  
    function getConfig( $name ){
      if (!isset($this->settings[$name])){
        return "";
      } else {
        return $this->settings[$name];
      }
    }
  
    function setConfig( $name, $value ){
      $this->settings[$name]=$value;
      $this->writeToFile();
    }
  
  }



?>
 
 
