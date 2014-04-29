<?php

  include('xIniTools.php');
  
  define('LOGIN_FAILED', 	10 );
  define('FILE_COUNT_FAILED', 	11 );
  define('SKETCH_FAILED', 	12 );
  define('PARENT_MD5_FAILED',	13 );
  
  define('UPLOAD_FAILED',	101 );
  define('MKDIR_FAILD',		102 );
  define('MISSING_FILE',	103 );
  define('SAME_PROJECT_EXISTS',	104 );
  

class SketchServerSync {


  private $login;
  private $file_count;
  private $sketch;
  private $parentMD5;
  
  function __construct(){
  
  }

  function check(){
    $this->login= getUrlParam( 'login' );
    if (empty($this->login)){
      return LOGIN_FAILED;
    }
    
    $this->file_count=getUrlParam('file_count');
    if (empty($this->file_count)){
      return FILE_COUNT_FAILED;
    }
    
    $this->sketch=getUrlParam('sketch');
    if (empty($this->sketch)){
      return SKETCH_FAILED;
    }

    $this->parentMD5=getUrlParam('parentmd5');
    if ($this->parentMD5 == ''){
      return PARENT_MD5_FAILED;
    }
    
  }
  
  function getFiles(){
  
    // generate md5 sum
    $count=0;
    $contents='';
    while ($count <= $this->file_count){
      $file = 'file'. $count++;
      
      // check if entry in array is set
      if ( isset($_FILES[$file])){
	$filename = $_FILES[$file]['tmp_name'];
	// check if file exists
	if (is_file($filename)){
	  echo "checking ".$filename."<br>";
	  $contents .= file_get_contents($filename );
	} else {
	  echo "skipping ".$filename."<br>";
	}
      
      }
    }
    $md5sum = md5( $contents );
    echo $md5sum.'<br>';
  
    // generate storate path
    $uploaddir = UPLOAD_DIR.$this->sketch.'/'.$md5sum.'/';
    //
    echo 'uploading '.$this->file_count.' file(s) to "'.$uploaddir.'"<br>';

    
    // reject upload of same project without changes twice (same md5sum)
    if (is_dir( $uploaddir )){
      return SAME_PROJECT_EXISTS;
    }

    // create directory
    if (!mkdir( $uploaddir, 0777, true )){
      return MKDIR_FAILD;
    }

    // now write the old MD5sum to file on server
    $md5ParentFile = $uploaddir.'md5.parent.sum';
    if (file_put_contents( $md5ParentFile, $this->parentMD5 ) == false){	
      echo 'failed to write parent md5';
    }
         
    // copy files into the target directory
    $count=0;
    while ($count < $this->file_count){
      $file = 'file'.$count++;
      
      // check if entry in array is set
      if ( isset($_FILES[$file])){
	
	$uploadfile = $uploaddir. basename( $_FILES[$file]['name'] );
    
	echo 'saving to '.$uploadfile.'<br>';
      
	if (is_file( $uploadfile)){
	  echo 'file allready exists and will be overwritten<br>';
	}
      
	if (move_uploaded_file( $_FILES[$file]['tmp_name'], $uploadfile )){
	  echo 'file uploaded<br>';
	} else {
	  echo 'failed to upload file<br>';
	  return UPLOAD_FAILED;
	}

      } else {
	return MISSING_FILE;
      }
    }  
    
    // now write the new MD5sum to file on server
    $md5File = $uploaddir.'md5.sum';
    
    if (is_File($md5File)){
      $text= file_get_contents( $md5File );
      echo 'old md5 is: '.$text.'<br>';
    }
    echo 'new md5 is: '.$md5sum.'<br>';
    
    if (file_put_contents( $md5File, $md5sum ) == true){	
      // send new md5 sum to client
      echo '<md5>'.$md5sum.'</md5>';
    } else {
      echo "unable to write to file ".$md5File.'<br>';
    }
    
    
    return 0;
  }
};

?>
