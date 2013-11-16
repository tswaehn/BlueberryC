<?php

class SketchConfig {
  
  private $sketch;
  private $iniFile;
  private $config;
  
  function __construct( $sketch ){
    // fixed folder structure
    $this->iniFile = './sketches/'.$sketch.'/sketch.ini';

    // remember current sketch name
    $this->sketch = $sketch;
  
    // read config from file
    $this->readConfig();
  
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
      $this->setConfig('info', 'thumbnail', './BlackLabel/sketches/'.$this->sketch.'/thumbnail.png' );
    }
    
    // wiring
    if ($this->getConfig('info', 'wiring') == ''){
      $this->setConfig('info', 'wiring', './BlackLabel/sketches/'.$this->sketch.'/wiring.jpg' );
    }
    
    // project ID
    if ($this->getConfig('info', 'projectID') == ''){
      $this->setConfig('info', 'projectID', uniqid('', true) );
    }
    
    // server side MD5 sum
    if ($this->getConfig('info','MD5-Sum') == ''){
      $this->setConfig('info','MD5-Sum', '0' );
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


 
  function writeConfig(){

    $content = ""; 
    foreach ($this->config as $key=>$elem) { 
	$content .= "[".$key."]\n"; 
	foreach ($elem as $key2=>$elem2) { 
	    if(is_array($elem2)) 
	    { 
		for($i=0;$i<count($elem2);$i++) 
		{ 
		    $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
		} 
	    } 
	    else if($elem2=="") $content .= $key2." = \n"; 
	    else $content .= $key2." = \"".$elem2."\"\n"; 
	} 
    } 
  
    file_put_contents( $this->iniFile, $content );
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
}

?>
