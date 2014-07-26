<?php


  define('EVERYTHING_FINE',     0);
  define('LOGIN_FAILED', 	10 );
  define('FILE_COUNT_FAILED', 	11 );
  define('SKETCH_FAILED', 	12 );
  define('PARENT_MD5_FAILED',	13 );
  define('COMMIT_MSG_FAILED',   14 );
  define('COMMIT_TYPE_FAILED',  15 );
  
  define('UPLOAD_FAILED',	101 );
  define('MKDIR_FAILD',		102 );
  define('MISSING_FILE',	103 );
  define('SAME_PROJECT_EXISTS',	104 );
  
  
  $errorStr = array( 
          EVERYTHING_FINE => "thanks",
          LOGIN_FAILED => "no valid login",
          FILE_COUNT_FAILED => "missing number of uploaded files",
          SKETCH_FAILED => "missing sketch name",
          PARENT_MD5_FAILED => "no parent project given",
          COMMIT_MSG_FAILED => "no commit message given", 
          COMMIT_TYPE_FAILED => "no commit type given",
          
          UPLOAD_FAILED => "failed to receive files",
          MKDIR_FAILD => "failed to store new project",
          MISSING_FILE => "waiting for more files",
          SAME_PROJECT_EXISTS => "this project already exists"         
  
  
        );
        
class SketchUpload {


  private $user;
  private $email;
  private $pwd;
  
  private $file_count;
  private $sketch;
  private $parentMD5;
  private $md5sum;
  private $uploaddir;
  private $files;
  private $revision;
  private $commitMsg;
  private $commitType;
  
  function __construct(){
  
  }

  function check(){
    $this->user= getUrlParam( 'user' );
    if (empty($this->user)){
      return LOGIN_FAILED;
    }
    $this->email= getUrlParam( 'email' );
    if (empty($this->email)){
      return LOGIN_FAILED;
    }
    $this->pwd= getUrlParam( 'pwd' );
    if (empty($this->pwd)){
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

    $this->commitMsg= getUrlParam( "commitmsg" );
    if ($this->commitMsg == ''){
      return COMMIT_MSG_FAILED;
    }

    $this->commitType= getUrlParam( "committype" );
    if ($this->commitType == ''){
      return COMMIT_TYPE_FAILED;
    }

    // check for valid user
    $accounts = new Accounts();
    if ($accounts->check( $this->user, $this->email, $this->pwd ) != 1){
      return LOGIN_FAILED;
    }
    
    
    
  }

  /*
      generates the target folder name for uploaded files.
      
  */
  function generateUploadDir(){
  
    // create a file list
    $files=array();
    foreach ($_FILES as $key=>$file){
        $tmpfile=$_FILES[$key]['tmp_name'];
        $name= $_FILES[$key]['name'];
        $files[$tmpfile]=$name;
    }
    
    // generate defined file order for MD5sum
    asort( $files, SORT_STRING );
    $contents = "";
    foreach ( $files as $tmpfile=>$name ){
      echo $tmpfile."\n";
      $contents .= file_get_contents($tmpfile );
    }
    $this->md5sum = md5( $contents );

    // create target directory
    $this->uploaddir = UPLOAD_DIR.$this->user.'/'.$this->sketch.'/'.$this->md5sum.'/';
  
  
    // count available revisions
    $dir= UPLOAD_DIR.$this->user.'/'.$this->sketch.'/';
    $revs=0;
    if (is_dir($dir)){
      // open handler for the directory
      $iter = new DirectoryIterator(  utf8_decode( $dir ) ); // php file access is always ISO-8859-1 
      foreach( $iter as $item ) {
          // make sure you don't try to access the current dir or the parent
          if ($item != '.' && $item != '..') {
                  if( $item->isDir() ) {    
                    $revs++;
                  }
            }
      }
    }
    $this->revision = $revs;
    
  }
  
  /*
      all received files are stored in the temp folder
      so just check if they really exist and count them.
      
      additional filters and checks for files can be applied here.
      
      \return: list of valid received files
      
  */
  function copyReceivedFiles(){
    

    //
    echo 'uploading to "'.$this->uploaddir.'"<br>';
    
    $count=0;
    $files=array();
    
    // go through all files which meant to be received
    while ($count <= $this->file_count){
      // get name
      $file = 'file'. $count++;
      
      // check if entry in array is set
      if ( isset($_FILES[$file])){
        $filename = $_FILES[$file]['tmp_name'];
        // check if file exists
        if (file_exists($filename)){
          echo "file ".$filename." is valid<br>";
          
          $basename= basename( $_FILES[$file]['name'] );
          $uploadfile = $this->uploaddir. $basename;
          $files[] = $basename;
          
          echo 'saving to '.$uploadfile.'<br>';
          
          if (file_exists( $uploadfile)){
            echo 'file allready exists and will be overwritten<br>';
          }

          if (move_uploaded_file( $_FILES[$file]['tmp_name'], $uploadfile )){
            echo 'done<br>';
          } else {
            echo 'failed to upload file<br>';
            return 0;
          }
          
          
        } else {
          echo "skipping ".$filename."<br>";
        }
      
      }
    }
  
    $this->files = $files;
    
    return 1;
  }
  
  /*
      stores information regarding the uploaded
      sketch
      
  */
  function generateAdditionalInfo(){
   
    // -- store information
    $file = $this->uploaddir."sketch-info.json";
    
    $data["sketch"] = $this->sketch;
    $data["parentMD5"] = $this->parentMD5;
    $data["md5sum"] = $this->md5sum;
    $data["timestamp"] = time();
    $data["revision"] = $this->revision; // [rev0, rev1, ...]
    $data["user"] = $this->user;
    $data["email"] = $this->email;
    $data["files"] = $this->files;
    $data["commitMsg"]= $this->commitMsg;
    $data["commitType"]= $this->commitType;
    
    $json_data = json_encode( $data );
        
    if ( file_put_contents( $file, $json_data ) == false){        
      echo 'failed to write sketch info';
      return 0;
    }
    
    return 1;
  }
  
  function importFiles(){
    global $returnData;

    
    // generate target folder for uploaded files
    $this->generateUploadDir();
    
    // reject upload if folder allready exists
    if (is_dir( $this->uploaddir )){
      return SAME_PROJECT_EXISTS;
    }

    // create directory
    if (!mkdir( $this->uploaddir, 0777, true )){
      return MKDIR_FAILD;
    }
  
    // move all files from temp to their destination folder
    if ( $this->copyReceivedFiles() == 0){      
      return UPLOAD_FAILED;
    }
    
    // create sketch information
    if ( $this->generateAdditionalInfo() ){ 
    
      // do not allow upload same sketch twice
      if ($this->md5sum == $this->parentMD5){
        return SAME_PROJECT_EXISTS;
      }
    
      // send new md5 sum to client
      $returnData["md5Parent"] = $this->md5sum;
    } else {
      echo "unable to write to sketch information <br>";
    }
    
    
    return 0;
  }  
};

?>
