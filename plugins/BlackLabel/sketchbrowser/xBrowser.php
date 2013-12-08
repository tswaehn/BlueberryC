<?php

  include('./sketchbrowser/xSketchConfig.php');
  

class Browser {

  private $sketchFolder;
  private $sketchTrash;

  function __construct( ){
    $this->sketchFolder = './sketches/';
    $this->sketchTrash = './trash/';
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
  
  function newSketch( $caption ){
    
    if ($caption==''){
      $caption='untitled';
    }
    
    echo 'creating new sketch with caption '.$caption.'<br>';
    
    // create project folder
    $sketch=uniqid('', true);
    $folder=$this->sketchFolder.$sketch.'/';
    if (!mkdir( $folder )){
      echo 'cannot create new sketch<br>';
      return;
    }
    
    // copy default thumbnail
    $thumbnail = 'default_thumbnail.jpg';
    $src= './sketchbrowser/'.$thumbnail;
    $dest=$folder.$thumbnail;
    if (!is_file($src)){
      echo 'default thumbnail '.$src.' does not exist<br>';
    } else {
      if (!copy( $src, $dest)){
	echo 'cannot copy '.$src.' to '.$dest.'<br>';
      }
    }
    
    // copy default wiring
    $wiring = 'default_wiring.jpg';
    $src= './sketchbrowser/'.$wiring;
    $dest=$folder.$wiring;
    if (!is_file($src)){
      echo 'default thumbnail '.$src.' does not exist<br>';
    } else {
      if (!copy( $src, $dest)){
	echo 'cannot copy '.$src.' to '.$dest.'<br>';
      }
    }    
      
    // copy default sketch(*.ino) file
    $sketchfile='default.ino';
    $src='./sketchbrowser/'.$sketchfile;
    $dest=$folder.$sketchfile;
    if (!is_file($src)){
      echo 'default sketch '.$src.' does not exist<br>';
    } else {
      if (!copy( $src, $dest)){
	echo 'cannot copy '.$src.' to '.$dest.'<br>';
      }
    }
    
    // copy default make file
    $makefile='makefile';
    $src='./sketchbrowser/'.$makefile;
    $dest=$folder.$makefile;
    if (!is_file($src)){
      echo 'default sketch '.$src.' does not exist<br>';
    } else {
      if (!copy( $src, $dest)){
	echo 'cannot copy '.$src.' to '.$dest.'<br>';
      }
    }    
      
    // create config file for new sketch
    $config= new SketchConfig( $sketch );
    $config->setConfig('info', 'caption', $caption );
    $config->setConfig('info', 'thumbnail', $sketch.'/'.$thumbnail );
    $config->setConfig('info', 'wiring', $sketch.'/'.$wiring );
    $config->setConfig('info', 'sketch', $sketch );
    
    $config->writeConfig();    
    
  
  }

  
  function removeSketch( $sketch ){
    
    $src = $this->sketchFolder.$sketch;
    $dest = $this->sketchTrash.$sketch;
    
    echo 'moving '.$src.' to '.$dest.'<br>';
    
    if (moveDirectory( $src, $dest )){
      echo 'done<br>';
    } else {
      echo 'failed<br>';
    }
    
  
  }

  function restoreSketch( $sketch ){
    
    $src = $this->sketchTrash.$sketch;
    $dest = $this->sketchFolder.$sketch;
    
    echo 'moving '.$src.' to '.$dest.'<br>';
    
    if (moveDirectory( $src, $dest )){
      echo 'done<br>';
    } else {
      echo 'failed<br>';
    }
    
  
  }

  function killSketch( $sketch ){
    
    $src = $this->sketchTrash.$sketch;
    
    echo 'finally killing '.$src.'<br>';
    
    if (deleteDirectory( $src )){
      echo 'done<br>';
    } else {
      echo 'failed<br>';
    }
    
  
  }

  


  function renderSketchShort( $sketch ){
    
    
    $config= new SketchConfig( $sketch );
      
    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = './BlackLabel/sketches/'.$config->getConfig('info','thumbnail');
    
    if (strlen($description) > 200){
      $description=substr( $description, 0, 200 ).' ... ';
    }
    
    $options = '<a href="'.linkToMe('view').'&sketch='.$sketch.'">view</a> ';
    $options.= '<a href="'.linkToMe('edit').'&sketch='.$sketch.'">edit</a> ';
    $options.= '<a href="'.linkToMe('setup').'&sketch='.$sketch.'">setup</a> ';
    $options.= '<a href="'.linkToMe('browse').'&sketch='.$sketch.'&do=del">delete</a> ';
    
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
    $thumbnail = './BlackLabel/sketches/'.$config->getConfig('info','thumbnail');
    $wiring = './BlackLabel/sketches/'.$config->getConfig('info','wiring');
    $projectID = $config->getConfig('info','sketch');
    
    $serverMD5 = $config->getConfig('info','MD5-Sum');
    $projectMD5 = $this->generateMyProjectMD5( $sketch );
    
    $options = '<a href="'.linkToMe('edit').'&sketch='.$sketch.'">edit</a> ';
    $options.= '<a href="'.linkToMe('setup').'&sketch='.$sketch.'">setup</a> ';
    $options.= '<a href="'.linkToMe('browse').'&sketch='.$sketch.'&do=del">delete</a> ';

    echo '<h3>View Sketch - '.$caption.'</h3>';
    
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
    echo '<div id="sketch_wiring"><img src="'.$wiring.'" width="400" /></div><br>';
    echo "</div>";

    //echo '<a href="'.linkToMe('filesStorage_del').'&file='.$filename.'">del</a>';
    //echo '<a href="'.linkToMe('burn').'&file='.$filename.'">burn</a>';  
    
  }

  function renderTrashShort( $sketch ){
    
    
    $config= new SketchConfig( $sketch, true );
      
    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = './BlackLabel/trash/'.$config->getConfig('info','thumbnail');
    
    if (strlen($description) > 200){
      $description=substr( $description, 0, 200 ).' ... ';
    }
    
    $options = '<a href="'.linkToMe('trash').'&sketch='.$sketch.'&do=restore">restore</a> ';
    $options.= '<a href="'.linkToMe('trash').'&sketch='.$sketch.'&do=kill">kill</a> ';
    
    echo '<div id="sketch_item">';
    echo '<div id="thumbnail"><img src="'.$thumbnail.'" width="32" height="32" /></div>';
    echo '<span id="caption">'.$caption.'</span>';
    echo '<span id="options">'.$options.'</span>';
    echo '<span id="description">'.$description.'</span>';
    echo "</div>";

    //echo '<a href="'.linkToMe('filesStorage_del').'&file='.$filename.'">del</a>';
    //echo '<a href="'.linkToMe('burn').'&file='.$filename.'">burn</a>';  
    
  }  
  
  
  function setupSketch( $sketch ){
  
    $config= new SketchConfig( $sketch );
      
    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = './BlackLabel/sketches/'.$config->getConfig('info','thumbnail');
    $wiring = './BlackLabel/sketches/'.$config->getConfig('info','wiring');
    
    echo '<h3>Setup Sketch - '.$caption.'</h3>';
    
    echo '<div id="sketch_setup">';
    echo 'Caption:<br>';
    echo '<form action="'.linkToMe('setup').'&sketch='.$sketch.'&do=save_caption" method="post">';
    echo '<input type="text" name="caption" value="'.$caption.'"><br>';
    echo '<br>';
    echo '<input type="reset" name="submit" value="Cancel">';
    echo '<input type="submit" name="submit" value="Save">';
    echo '</form>';   
    echo '</div>';

    echo '<div id="sketch_setup">';
    echo 'Thumbnail:<br>';
    echo '<img src="'.$thumbnail.'" width="64" height="64" />';
    echo '<form action="'.linkToMe('setup').'&sketch='.$sketch.'&do=save_thumbnail" method="post" enctype="multipart/form-data">';
    echo '<label for="file">Filename:</label>';
    echo '<input type="file" name="thumbnail"><br>';
    echo '<input type="submit" name="submit" value="Upload">';
    echo '</form>';
    echo '</div>';
    
    echo '<div id="sketch_setup">';
    echo 'Description:<br>';
    echo '<form action="'.linkToMe('setup').'&sketch='.$sketch.'&do=save_description" method="post">';
    echo '<textarea name="description" rows="20" cols="70">'.$description.'</textarea>';
    echo '<br>';
    echo '<input type="reset" name="submit" value="Cancel">';
    echo '<input type="submit" name="submit" value="Save">';
    echo '</form>';   
    echo '</div>';
    
    echo '<div id="sketch_setup">';
    echo 'Wiring:<br>';
    echo '<div id="sketch_wiring"><img src="'.$wiring.'" width="400" /></div><br>';
    echo '<form action="'.linkToMe('setup').'&sketch='.$sketch.'&do=save_wiring" method="post" enctype="multipart/form-data">';
    echo '<label for="file">Filename:</label>';
    echo '<input type="file" name="wiring"><br>';
    echo '<input type="submit" name="submit" value="Upload">';
    echo '</form>';
    echo '</div>';
    
    echo '<p>';

  }
  
  function saveSketchCaption( $sketch ){

    $config= new SketchConfig( $sketch );
    
    $caption = getUrlParam( 'caption' );
    
    $config->setConfig('info','caption', $caption );
    $config->writeConfig();

    echo 'saved caption<br>';
  
  
  }
  
  function saveSketchThumbnail( $sketch ){

    $destination = './sketches/'.$sketch.'/';
    
    $file = moveUploadedFile( 'thumbnail', $destination );
    
    if ($file){

      $config= new SketchConfig( $sketch );
      
      $thumbnail = $sketch.'/'.$file['filename'];
      
      $config->setConfig('info','thumbnail', $thumbnail );
      $config->writeConfig();
    
      echo 'saved thumbnail '.$thumbnail.'<br>';
    
    } else {
  
  
      echo 'failed to saved thumbnail<br>';
      
    }
  
  
  }
  function saveSketchDescription( $sketch ){

    $config= new SketchConfig( $sketch );
    
    $description = getUrlParam( 'description' );
    
    $config->setConfig('info','description', $description );
    $config->writeConfig();
    
    echo 'saved description<br>';
  
  
  }

  function saveSketchWiring( $sketch ){
    
    $destination = './sketches/'.$sketch.'/';
    
    $file = moveUploadedFile( 'wiring', $destination );
    
    if ($file){

      $config= new SketchConfig( $sketch );
      
      $wiring = $sketch.'/'.$file['filename'];
      
      $config->setConfig('info','wiring', $wiring );
      $config->writeConfig();
    
      echo 'saved thumbnail '.$wiring.'<br>';
    
    } else {
  
  
      echo 'failed to saved thumbnail<br>';
      
    }
  
  
  }
  
  function display(){

    $dirList = getDirectoryListing( $this->sketchFolder );
    
    if (is_array($dirList)){
      foreach ($dirList as $item){
	$this->renderSketchShort($item);
      }
    }
    
  }

  function displayTrash(){

    $dirList = getDirectoryListing( $this->sketchTrash );
    
    if (is_array($dirList)){
      foreach ($dirList as $item){
	$this->renderTrashShort($item);
      }
    }
    
  }
  
} 

?>
