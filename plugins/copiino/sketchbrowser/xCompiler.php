<?php

  include( PLUGIN_DIR.'sketchbrowser/xSketchConfig.php');
  
class Compiler {

  private $sketchFolder;

  function __construct(){
    $this->sketchFolder = PLUGIN_DIR.'sketches/';

  }
 

  function lookForInoFile( $sketch ){
  
    $files = glob( $this->sketchFolder.$sketch.'/*.ino' );

    if (!empty($files)){
      // take always the first entry if multiple files exist
      $file=$files[0];
      return $file;
    } else {
      echo 'no *.ino file found<br>';
    }

    return null;
  }
  

  

  function loadFromFile( $file ){
   
    $text='';
    
    // check if file is existing
    if ((isset($file)) && (is_file($file))){
      $text=file_get_contents( $file );
    } else {
      echo 'failed to load '.$file.'<br>';
    }
    
    return $text;
  }

  function saveToFile( $sketch, $text ){
    
    if ($sketch==''){
      echo 'no sketch given<br>';
      return;
    }
    
    if ($text == ''){
      echo 'nothing to write<br>';
      return;
    }
    
    // find a file for saving into
    $file = $this->lookForInoFile( $sketch );
    
    // if no file exists, ... create one
    if (empty($file)){
      $file = $this->sketchFolder.$sketch.'/'.'default.ino';
      echo 'creating new file '.$file.'<br>';
    }
    
    if (!file_put_contents( $file, $text )){
      echo 'failed to write to '.$file.'<br>';
    }

  }
  
  function compile($sketch, $text){
    
    $this->saveToFile( $sketch, $text );
    $inoFile = $this->lookForInoFile($sketch);
    
    startProcess( PLUGIN_DIR.'sketchbrowser/execute_make.sh \''.$sketch.'\' \''.$inoFile.'\'', getUrlParam('pageId'), '', 'x', 0 );
    
  }
  
  function compileAndRun($sketch, $text){
    
    $this->saveToFile( $sketch, $text );
    $inoFile = $this->lookForInoFile($sketch);
    
    startProcess( PLUGIN_DIR.'sketchbrowser/execute_make_and_run.sh \''.$sketch.'\' \''.$inoFile.'\'', getUrlParam('pageId'), '', 'x', 0 );
    
  }
  
  function editor($sketch){
    
    $file = $this->lookForInoFile($sketch);
    
    $text = $this->loadFromFile($file); 
    
    echo '<form action="'.linkToMe('edit').'&sketch='.$sketch.'" method="post">';
    echo '<button type="sbumit" name="do" value="cancel">Cancel</button>';
    echo '<button type="submit" name="do" value="save">Save</button>';
    echo '<button type="submit" name="do" value="compile">Compile</button>';    
    echo '<button type="submit" name="do" value="compile_and_run">Compile and Run ...</button>';
    echo '<textarea name="text" cols="80" rows="25">';

    echo $text;
    
    echo '</textarea>';
    echo '</form>';   
  
  }
  
  function edit($sketch){

  
    $config= new SketchConfig( $sketch );
      
    $caption = $config->getConfig('info','caption');
    $description = $config->getConfig('info','description');
    $thumbnail = PLUGIN_DIR.'sketches/'.$config->getConfig('info','thumbnail');
    $wiring = PLUGIN_DIR.'sketches/'.$config->getConfig('info','wiring');
    $projectID = $config->getConfig('info','sketch');
    $cpp = $config->getConfig('cpp', 'file');
    
    $serverMD5 = $config->getConfig('info','MD5-Sum');
    $options = '<a href="'.linkToMe('view').'&sketch='.$sketch.'">view</a> ';

    if (strlen($description) > 200){
      $description=substr( $description, 0, 200 ).' ... ';
    }    
      //$text = loadFromFile($file);
    
    // header output
    echo '<h3>Edit Sketch - '.$caption.'</h3>';

    echo '<div id="sketch_item">';

    // sketch short description
    echo '<div id="thumbnail"><img src="'.$thumbnail.'" width="32" height="32" /></div>';
    echo '<span id="caption">'.$caption.'</span>';
    echo '<span id="options">'.$options.'</span>';
    echo '<span id="description">'.$description.'</span>';
    
    // the editor
    echo '<p>';
    $this->editor($sketch);

    // compile output
    echo '<div id="log"></div>';
        
    // the wiring schematic
    echo '<div id="sketch_wiring"><img src="'.$wiring.'" width="400" /></div><br>';

    // additional files
    echo '<div>';
    echo 'Additional library files:<br>';
      //
      if (!empty($cpp)){
	echo '<ul>';
	foreach ($cpp as $file){
	  $show='<a href="'.PLUGIN_DIR.'sketches/'.$sketch.'/'.$file.'" style="padding-left:10px;padding-right:10px" target="_blank">show</a>';
	  echo '<li>'.$file.' '.$show.'</li>';
	}
	echo '</ul>';
      }
    echo "</div>";

    echo '</div>';
    
    echo "</div>";
    
    
  }

}



?>
