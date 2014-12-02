<?php
  
  
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
    
  function jsonCurlReturn( $array ){
    
    $contents = json_encode( $array );
    
    $contents = "<CONTENTS>". $contents. "</CONTENTS>";
    
    echo $contents;    
  
  }
  
  
?>    
