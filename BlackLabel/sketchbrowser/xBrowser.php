<?php

  include('./sketchbrowser/xSketchConfig.php');
  

class Browser {

  private $sketchFolder;

  function __construct( ){
    $this->sketchFolder = './sketches/';
    
  
  }

  function generateMyProjectMD5( $sketch ){
    // check all files
    $dir = $this->sketchFolder.'/'.$sketch.'/';
    $text = '';
    if ($handle = opendir( $dir )) {

      while (false !== ($file = readdir($handle))) {
	  if ($file != "." && $file != "..") {
	    if (is_file($dir.$file)){
	      //echo "checking ".$file."<br>";
	      $text .= file_get_contents($dir.$file );
	    } else {
	      //echo "skipping ".$file."<br>";
	    }
	  }    
      }
      closedir($handle);
    } else {
      echo "failed to check directory";
    }
   
    return md5( $text );
    
  }
  
  function renderSketchShort( $sketch ){
    
    
    $config= new SketchConfig( $sketch );
      
    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = $config->getConfig('info','thumbnail');
    
    $options = '<a href="'.linkToMe('view').'&sketch='.$sketch.'">view</a> <a href="'.linkToMe('edit').'&sketch='.$sketch.'">edit</a> <a href="">setup</a>';
    
    echo '<div id="sketch_item">';
    echo '<div id="thumbnail"><img src="'.$thumbnail.'" width="32" height="32" /></div>';
    echo '<span id="caption">'.$caption.'</span>';
    echo '<span id="options">'.$options.'</span>';
    echo '<span id="description">'.$description.'</span>';
    echo "</div>";

    //echo '<a href="'.linkToMe('filesStorage_del').'&file='.$filename.'">del</a>';
    //echo '<a href="'.linkToMe('burn').'&file='.$filename.'">burn</a>';  
    
  }

  function renderSketch( $sketch ){
    
    
    $config= new SketchConfig( $sketch );
      
    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = $config->getConfig('info','thumbnail');
    $wiring = '<img src="'.$config->getConfig('info','wiring').'" width="400" />';
    $projectID = $config->getConfig('info','projectID');
    
    $serverMD5 = $config->getConfig('info','MD5-Sum');
    $projectMD5 = $this->generateMyProjectMD5( $sketch );
    
    $options = '<a href="'.linkToMe('edit').'&sketch='.$sketch.'">edit</a> <a href="">setup</a>';
    
    echo '<div id="sketch_view">';
    echo '<div id="sketch_icon"><img src="'.$thumbnail.'" width="64" height="64" /></div>';
    echo '<span id="sketch_caption">'.$caption.'</span><br>';
    echo '<span id="sketch_options">'.$options.'</span><br>';

    echo '<div id="sketch_projectid">';
    echo '<span id="sketch_projectid">projectID '.$projectID.'</span><br>';
    echo '<span id="sketch_md5">server MD5 '.$serverMD5.'</span><br>';
    echo '<span id="sketch_md5">project MD5 '.$projectMD5.'</span><br>';
    echo '</div>';
    
    echo '<span id="sketch_description">'.$description.'</span><br>';
    echo '<div id="sketch_wiring">'.$wiring.'</div><br>';
    echo "</div>";

    //echo '<a href="'.linkToMe('filesStorage_del').'&file='.$filename.'">del</a>';
    //echo '<a href="'.linkToMe('burn').'&file='.$filename.'">burn</a>';  
    
  }

  function display(){

  
    if (!isset($this->sketchFolder)){
      echo "sketch folder is not set";
      return;
    }
      
    if (!is_dir($this->sketchFolder)){
      echo "sketch folder does not exist";
      return;
    } 
    
    if ($handle = opendir( $this->sketchFolder )) {

      while (false !== ($file = readdir($handle))) {
	  if ($file != "." && $file != "..") {
	      $this->renderSketchShort($file);
	  }    
      }
      closedir($handle);
    } else {
      echo "failed to check directory";
    }
  }

} 

?>
