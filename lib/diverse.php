<?php

  date_default_timezone_set('Europe/London');

  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );


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

  
 
  function getAction(){
      return getGlobal('action');
  }
 
    
  function sanitizeDir( $directory ){
    // do some checks
    $sanDir = $directory;
    // return 
    return $sanDir;
  }
  
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
  
  function timeDiffToString( $delta ){

    // const :-)
    $SECOND = 1;
    $MINUTE = 60 * $SECOND;
    $HOUR = 60 * $MINUTE;
    $DAY = 24 * $HOUR;
    $MONTH = 30 * $DAY;

    // store the used timezone
    $origTimeZone=date_default_timezone_get();
    
    // set to UTC
    date_default_timezone_set('UTC');
  
    // convert (use UTC!!!)
    $deltaArr = getdate( $delta );
    
    // switch back to orig
    date_default_timezone_set( $origTimeZone );
       
    // get time to secs, mins, hours, ... etc.
    $Seconds = $deltaArr["seconds"];
    $Minutes = $deltaArr["minutes"];
    $Hours = $deltaArr["hours"];
    
    $Days = $deltaArr["mday"] - 1 ; // hack to get starting with "0"
    
    $Months = $deltaArr["mon"] - 1; // hack to get starting with "0"
    $Years = $deltaArr["year"] - 1970; // hack to get starting with "0"

    

    
    // output debug
    //echo " [".$delta."] ".$Years." ".$Months." ".$Days." ".$Hours." ".$Minutes." ".$Seconds." ";
    
    if ($delta < 0)
    {
      return "not yet";
    }
    if ($delta < 1 * $MINUTE)
    {
      return $Seconds == 1 ? "one second ago" : $Seconds . " seconds ago";
    }
    if ($delta < 2 * $MINUTE)
    {
      return "a minute ago";
    }
    if ($delta < 45 * $MINUTE)
    {
      return $Minutes . " minutes ago";
    }
    if ($delta < 90 * $MINUTE)
    {
      return "an hour ago";
    }
    if ($delta < 24 * $HOUR)
    {
      return $Hours . " hours ago";
    }
    if ($delta < 48 * $HOUR)
    {
      return "yesterday";
    }
    if ($delta < 30 * $DAY)
    {
      return $Days . " days ago";
    }
    if ($delta < 12 * $MONTH)
    {
      return $Months <= 1 ? "one month ago" : $Months . " months ago";
    }
    else
    {
      return $Years <= 1 ? "one year ago" : $Years . " years ago";
    }
  }  
    
?>
