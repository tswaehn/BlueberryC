<?php

  include( PLUGIN_DIR.'sketchbrowser/xSketchConfig.php');
  include( PLUGIN_DIR.'sync/xSketchUpload.php');
  
  include( PLUGIN_DIR."/config/jsonSettings.php");
  include( PLUGIN_DIR."/config/copiinoSettings.php" );
    
  

class Browser {

  private $sketchFolder;
  private $sketchTrash;

  function __construct( ){
    $this->sketchFolder = PLUGIN_DIR.'sketches/';
    $this->sketchTrash = PLUGIN_DIR.'trash/';
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
    $src= PLUGIN_DIR.'sketchbrowser/'.$thumbnail;
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
    $src= PLUGIN_DIR.'sketchbrowser/'.$wiring;
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
    $src= PLUGIN_DIR.'sketchbrowser/'.$sketchfile;
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
    $src= PLUGIN_DIR.'sketchbrowser/'.$makefile;
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
    $thumbnail = PLUGIN_DIR.'sketches/'.$config->getConfig('info','thumbnail');
    
    if (strlen($description) > 200){
      $description=substr( $description, 0, 200 ).' ... ';
    }
    
    $options = '<a href="'.linkToMe('view').'&sketch='.$sketch.'">view</a> ';
    $options.= '<a href="'.linkToMe('edit').'&sketch='.$sketch.'">edit</a> ';
    $options.= '<a href="'.linkToMe('browse').'&sketch='.$sketch.'&do=upload">upload</a> ';
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
    $thumbnail = PLUGIN_DIR.'sketches/'.$config->getConfig('info','thumbnail');
    $wiring = PLUGIN_DIR.'sketches/'.$config->getConfig('info','wiring');
    $projectID = $config->getConfig('info','sketch');
    $cpp = $config->getConfig('cpp', 'file');

    $serverMD5='0';
    $md5File=  PLUGIN_DIR.'sketches/'.$sketch.'/'.'md5.sum';
    // display old md5sum
    if (is_File($md5File)){
      $serverMD5= file_get_contents( $md5File );
    }
    
    $options = '<a href="'.linkToMe('edit').'&sketch='.$sketch.'">edit</a> ';
    $options.= '<a href="'.linkToMe('browse').'&sketch='.$sketch.'&do=upload">upload</a> ';
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
    echo '</div>';
    
    echo '<span id="sketch_description">'.$description.'</span><br>';
    echo '<div id="sketch_wiring"><img src="'.$wiring.'" width="400" /></div><br>';

    echo '<span id="sketch_description">';
    if (!empty($cpp)){
      echo 'additional library files for this sketch:';
      echo '<ul>';
      foreach ($cpp as $file){
	echo '<li>'.$file.'</li>';
      }
      echo '</ul>';
    }
    echo '</span><br>';

    echo "</div>";

    //echo '<a href="'.linkToMe('filesStorage_del').'&file='.$filename.'">del</a>';
    //echo '<a href="'.linkToMe('burn').'&file='.$filename.'">burn</a>';  
    
  }

  function renderTrashShort( $sketch ){
    
    
    $config= new SketchConfig( $sketch, true );
      
    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = PLUGIN_DIR.'trash/'.$config->getConfig('info','thumbnail');
    
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
    $thumbnail = PLUGIN_DIR.'sketches/'.$config->getConfig('info','thumbnail');
    $wiring = PLUGIN_DIR.'sketches/'.$config->getConfig('info','wiring');
    $cpp = $config->getConfig('cpp','file');
    
    echo '<h3>Setup Sketch - '.$caption.'</h3>';
    
    echo '<div id="sketch_setup">';
    echo 'Caption: ';
    echo '<form action="'.linkToMe('setup').'&sketch='.$sketch.'&do=save_caption" method="post">';
    echo '<input type="text" name="caption" value="'.$caption.'">';
    echo '<input type="reset" name="submit" value="Cancel">';
    echo '<input type="submit" name="submit" value="Save">';
    echo '</form>';   
    echo '</div>';

    echo '<div id="sketch_setup">';
    echo 'Thumbnail:<br>';
    echo '<img src="'.$thumbnail.'" width="64" height="64" style="float:left"/>';
    echo '<div >';
    echo '<form action="'.linkToMe('setup').'&sketch='.$sketch.'&do=save_thumbnail" method="post" enctype="multipart/form-data">';
    echo '<label for="file">Filename:</label>';
    echo '<input type="file" name="thumbnail"><br>';
    echo '<input type="submit" name="submit" value="Upload">';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    
    echo '<div id="sketch_setup">';
    echo 'Description:<br>';
    echo '<form action="'.linkToMe('setup').'&sketch='.$sketch.'&do=save_description" method="post">';
    echo '<textarea name="description" rows="5" cols="70">'.$description.'</textarea>';
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

    echo '<div id="sketch_setup">';
    echo 'Additional library files:<br>';
    //
    if (!empty($cpp)){
      echo '<ul>';
      foreach ($cpp as $file){
	$del='<a href="'.linkToMe('setup').'&sketch='.$sketch.'&do=del_cpp&file='.$file.'" style="padding-left:10px;padding-right:10px">del</a>';
	$show='<a href="'.PLUGIN_DIR.'sketches/'.$sketch.'/'.$file.'" style="padding-left:10px;padding-right:10px" target="_blank">show</a>';
	echo '<li>'.$del.' '.$file.' '.$show.'</li>';
      }
      echo '</ul>';
    }

    echo '<form action="'.linkToMe('setup').'&sketch='.$sketch.'&do=add_cpp" method="post" enctype="multipart/form-data">';
    echo '<label for="file">Filename:</label>';
    echo '<input type="file" name="cpp"><br>';
    echo '<input type="submit" name="submit" value="Upload">';
    echo '</form>';
    echo '</div>';
    
   
    
    echo '<p>';

  }
  
  function uploadSketch( $sketch ){
    
    $sync = new SketchSync();
    $config= new SketchConfig( $sketch );

    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = PLUGIN_DIR.'sketches/'.$config->getConfig('info','thumbnail');
    $wiring = PLUGIN_DIR.'sketches/'.$config->getConfig('info','wiring');
    $cpp = $config->getConfig('cpp','file');

    $md5File=  PLUGIN_DIR.'sketches/'.$sketch.'/'.'md5.sum';
    $old_md5sum='0';
    // display old md5sum
    if (is_File($md5File)){
      $old_md5sum= file_get_contents( $md5File );
      echo 'old md5 is: '.$old_md5sum.'<br>';
    }
    
    echo '<pre>';
    echo 'uploading '.$caption.' ('.$sketch.')<br>';
      
    $files = array();
    $files[] = $config->getFilename();
    $files[] = PLUGIN_DIR.'sketches/'.$sketch.'/'.'default.ino';
    $files[] = $thumbnail;
    $files[] = $wiring ;
    foreach ($cpp as $file ){
      $files[] = PLUGIN_DIR.'sketches/'.$sketch.'/'.$file;
    }

    // load copiino account settings
    $settings = new CopiinoSettings();
    $user= $settings->getConfig( "user" );
    $email = $settings->getConfig( "email" );
    $pwd = $settings->getConfig( "pwd" );

    $args = array();
    $args['user']= $user;
    $args['email']= $email;
    $args['pwd']= $pwd;
    
    $args['sketch']=$sketch;
    $args['parentmd5']=$old_md5sum;
    
    
    // send files and data
    $result = $sync->sendFiles( $files, $args );
    
    if ($result["error"]){
    
      echo $result["msg"];

    } else {
      // parse for the new md5sum
      if (isset($result["data"]["md5Parent"])){
      
        $md5sum = $result["data"]["md5Parent"];
        
        if (!empty( $md5sum )){
          $md5File=  PLUGIN_DIR.'sketches/'.$sketch.'/'.'md5.sum';
        
          if (file_put_contents( $md5File, $md5sum ) == false){
            echo "unable to write to file ".$md5File.'<br>';
          }	
          
          echo 'new md5: '.$md5sum.'<br>';
        }
      } else {
        echo 'error: cannot receive new md5sum<br>';
      }
    
    }
    
    
    echo '</pre>';
    
  }
  
  function saveSketchCaption( $sketch ){

    $config= new SketchConfig( $sketch );
    
    $caption = getUrlParam( 'caption' );
    
    $config->setConfig('info','caption', $caption );
    $config->writeConfig();

    echo 'saved caption<br>';
  
  
  }
  
  function saveSketchThumbnail( $sketch ){

    $destination = PLUGIN_DIR.'sketches/'.$sketch.'/';
    
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
    
    $destination = PLUGIN_DIR.'sketches/'.$sketch.'/';
    
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

  function addCppFile( $sketch ){

    $destination = PLUGIN_DIR.'sketches/'.$sketch.'/';
    
    $file = moveUploadedFile( 'cpp', $destination );
    
    if ($file){

      $config= new SketchConfig( $sketch );
      
      $cppfile = $file['filename'];
      
      // load allready existing files
      $filelist=$config->getConfig('cpp','file');
      // add uploaded
      $filelist[]=$cppfile;
      // write back to config
      $config->setConfig('cpp','file', $filelist );
      $config->writeConfig();
    
      echo 'added cpp file '.$cppfile.'<br>';
    
    } else {
  
  
      echo 'failed to add file<br>';
      
    }
  }  
  
  function delCppFile( $sketch ){
    
    $file = getUrlParam('file');
    $destination = PLUGIN_DIR.'sketches/'.$sketch.'/';

    // remove the file from disk
    if (is_file( $destination.$file)){
      if (unlink( $destination.$file)){
	echo 'removed file '.$file.'<br>';
      } else {
	echo 'failed to remove '.$file.'<br>';
      }
    } else {
      echo 'file '.$file.' does not exist<br>';
    }

    // remove the file from config
    $config= new SketchConfig( $sketch );
    // load allready existing files
    $filelist=$config->getConfig('cpp','file');
    
    // find entry and remove
    
    if (($entry=array_search( $file, $filelist )) !== NULL){
      unset($filelist[$entry]);
      // write back to config
      $config->setConfig('cpp','file', $filelist );
      $config->writeConfig();    
      echo 'removed from config<br>';
    } else {
      echo 'file '.$file.' not in config<br>';
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
