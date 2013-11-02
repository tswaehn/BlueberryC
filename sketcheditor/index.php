
<?php

  if (!isset($url_sketchstate)){
    $sketchstate='';
  } else {
    $sketchstate=$url_sketchstate;
  }

  switch ($sketchstate){
   case 'compile': include( './sketcheditor/compile.php'); break;   
   
    default:
	      include( './sketcheditor/editorShow.php' );
  
  }
  
  
?>

 
