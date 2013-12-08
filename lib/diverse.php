<?php

  function getGlobal( $var ){
    
    if (!isset($GLOBALS[$var])){
      $ret='';
    } else {
      $ret=$GLOBALS[$var];
    }

    return $ret;
  }

  function getUrlParam( $var ){
  
    $urlParam = 'url_'.$var;
    
    $ret = getGlobal( $urlParam );
    return $ret;
  }

  
   
   
  date_default_timezone_set('Europe/London');

  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );

  
  function moveUploadedFile( $uploadName, $targetDir ){
  
    $_FILES = getGlobal('_FILES');
        
    if (!isset($_FILES)){
      echo 'did not receive upload<br>';
      return 0;
    } else {
      print_r( $_FILES );
    }
     
    if ($_FILES[$uploadName]["error"] > 0){
      echo "Error: " . $_FILES[$uploadName]["error"] . "<br>";
      
    } else {
      echo "Upload: " . $_FILES[$uploadName]["name"] . "<br>";
      echo "Type: " . $_FILES[$uploadName]["type"] . "<br>";
      echo "Size: " . ($_FILES[$uploadName]["size"] / 1024) . " kB<br>";
      echo "Stored in: " . $_FILES[$uploadName]["tmp_name"];

      echo '<p>';
      
      $source = $_FILES[$uploadName]["tmp_name"];
      $filename = $_FILES[$uploadName]["name"];
      $destination = $targetDir.$filename;
      
      if ( move_uploaded_file( $source, $destination) == 1){
	echo 'moved file from '.$source.' to '.$destination.'<br>';
	
	return array('dest'=>$destination, 'filename'=>$filename );
      } else {
	echo 'failed to move file from '.$source.' to '.$destination.'<br>';
	return 0;
      }

    }
    
  }
  
  function getDirectoryListing( $directory ){
    
    $list=null;
    
    if (!isset($directory)){
      echo "folder is not set";
      return $list;
    }
	
    if (!is_dir($directory)){
      echo "folder does not exist";
      return $list;
    } 

    // now try to open directory
    if ($handle = opendir( $directory )) {
      
      $list=array();

      // go through all items 
      while (false !== ($dir = readdir($handle))) {
	    if ($dir != "." && $dir != "..") {
		// in case we found a directory, ... add it to the list
		if (is_dir($directory.$dir)){
		  $list[] = $dir;
		}
	    }    
      }
      
      // finally free the handle
      closedir($handle);

      // sort by name
      rsort($list, SORT_LOCALE_STRING); 
      
      return $list;
      
    } else {
      echo "failed to list directory";
      return $list;
    }  
    
  }
  
  function moveDirectory( $src, $dest ){
  
    if ((!isset($src)) || (!isset($dest))){
      echo "folder is not set";
      return null;
    }
	
    if (!is_dir($src)){
      echo "folder does not exist";
      return null;
    } 
    
    $ret = rename( $src, $dest );
    
    return $ret;
  }
  
  function deleteDirectory( $directory ){
  
    if (!isset($directory)){
      echo "folder is not set";
      return null;
    }
	
    if (!is_dir($directory)){
      echo "folder does not exist";
      return null;
    } 
    
    $files = array_diff(scandir($directory), array('.','..'));    
    
    foreach ($files as $file) { 
	echo 'removing '.$file.'<br>';
	(is_dir("$directory/$file")) ? delTree("$directory/$file") : unlink("$directory/$file"); 
    }
    
    return rmdir($directory);
    
    /*    
    
    from http://stackoverflow.com/questions/3349753/delete-directory-with-files-in-it
    
    function delTree($dir)
    { 
        $files = array_diff(scandir($dir), array('.','..')); 

        foreach ($files as $file) { 
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
        }

        return rmdir($dir); 
    }    
    */
  }
  
    
?>
