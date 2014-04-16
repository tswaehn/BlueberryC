<?php


class IniTools {
  
  private $iniFile;
  private $config;
  
  function __construct( $filename ){
    // fixed folder structure
    $this->iniFile =  $filename;
  
    // read config from file
    $this->readConfig();
  
  }
  
  function parseConfig(){
    //
   
  }
  
   function readConfig(){
    
    if (is_file( $this->iniFile )){
      $this->config = parse_ini_file( $this->iniFile, true );
    } else {
      $this->config = array();
      
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

