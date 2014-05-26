<?php

 class Sketch {
    
    public $sketch;
    public $caption;
    public $md5sum;
    public $parentMd5;
    public $thumbnail;
    public $description;
    public $timestamp;
    public $contributors;
    
    
    public $children;
    
    function __construct(){
      $this->children=array();
      $this->contributors=array("tswaehn" , "andreas" );
    }
    
    function createFromArray( $array ){
      foreach ($array as $key=>$value){
      
        if ($key == "children"){
          foreach ($value as $childValue){
            $child = new Sketch();
            $child->createFromArray( $childValue );
            
            $this->children[] = $child;
          }
        } else {
          $this->$key = $value;
        }
      }
      
      
    }
    
    function addSketch( $sketch ){
      if (strcmp( $this->md5sum,$sketch->parentMd5)==0){
        // well this is obviously the parent
        $this->children[] = $sketch;
        return 1;
      }
    
      foreach($this->children as $child){
        if ($child->addSketch( $sketch )){
          return 1;
        }
      }
      
      return 0;
    }
    
  } // end class
  
?>
