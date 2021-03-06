<?php

class SketchConfig {
  
  private $sketch;
  private $iniFile;
  private $config;
  
  function __construct(){
  }
  
  
  public static function loadByIni( $filename ){
    
    $obj = new SketchConfig();
    
    $obj->iniFile = $filename;
    
    // read config from file
    $obj->readConfig();
    
    // remember current sketch name
    $obj->sketch = $obj->getConfig("info", "sketch");
    
    return $obj;
  }
  
  public static function getSketchFolder( $sketch ){
      return PLUGIN_DIR.'sketches/'.$sketch.'/';      
  }
  
  public static function loadFromSketchFolder( $sketch ){
    $obj= SketchConfig::loadByIni( PLUGIN_DIR.'sketches/'.$sketch.'/sketch.ini' );
    return $obj;
  }
  
  public static function loadFromTrashFolder( $sketch ){
    $obj= SketchConfig::loadByIni(  PLUGIN_DIR.'trash/'.$sketch.'/sketch.ini' );
    return $obj;
  }

  public static function loadByText( $text ){
    
    $obj = new SketchConfig();
    
    $obj->iniFile ="";
    
    // read config from file
    $obj->readConfigFromString( $text );
    
    // remember current sketch name
    $obj->sketch = $obj->getConfig("info", "sketch");
    
    return $obj;    
    
  }
  
  function parseConfig(){

    // caption
    if ($this->getConfig('info', 'caption') == ''){
      $this->setConfig('info', 'caption', $this->sketch );
    }

    // description
    if ($this->getConfig('info', 'description') == ''){
      $this->setConfig('info', 'description', 'This is the description field and thus should not be left blank.');
    }
    
    // thumbnail
    if ($this->getConfig('info', 'thumbnail') == ''){
      $this->setConfig('info', 'thumbnail', $this->sketch.'/thumbnail.png' );
    }
    
    // wiring
    if ($this->getConfig('info', 'wiring') == ''){
      $this->setConfig('info', 'wiring', $this->sketch.'/wiring.jpg' );
    }
    
    // sketch (just for info)
    if ($this->getConfig('info', 'sketch') == ''){
      $this->setConfig('info', 'sketch', $this->sketch );
    }
    
    // additional *.cpp files which need to be compiled
    if ($this->getConfig('cpp','file') == ''){
      $this->setConfig('cpp', 'file', array() );
    }

  }
  
   function readConfig(){
    
    if (is_file( $this->iniFile )){
      $this->config = parse_ini_file( $this->iniFile, true );
    } else {
      $this->config = array();;
      
    }
  
    $this->parseConfig();
    $this->writeConfig();

  }
  
  function readConfigFromString($text){
    
    $this->config = parse_ini_string( $text, true );
  
    $this->parseConfig();
    //$this->writeConfig();
  
  }


 
  function writeConfig(){

    $content = ""; 
    foreach ($this->config as $key=>$elem) { 
	$content .= "[".$key."]\n"; 
	foreach ($elem as $key2=>$elem2) { 
	    if(is_array($elem2)) 
	    { 
		/*
		  note: array can have variouse keys then $i wont iterate
		for($i=0;$i<count($elem2);$i++) 
		{ 
		    $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
		} 
		*/
		foreach( $elem2 as $elem2key=>$elem2value ){
		    $content .= $key2."[] = \"".$elem2value."\"\n";
		}
	    } 
	    else if($elem2=="") $content .= $key2." = \n"; 
	    else $content .= $key2." = \"".$elem2."\"\n"; 
	} 
    } 
  
    if (file_put_contents( $this->iniFile, $content ) == false){
      echo "unable to write to file ".$this->iniFile.'<br>';
    }
  }
  
  
  function getConfig( $section, $param ){
    if (isset( $this->config[$section][$param])){
      return  $this->config[$section][$param];
    } else {
      return '';
    }
  }
  
  function setConfig( $section, $param, $value ){
  
    $this->config[$section][$param]= $value;  
  }
  
  function getFilename(){
    return $this->iniFile;
  }
}

?>
