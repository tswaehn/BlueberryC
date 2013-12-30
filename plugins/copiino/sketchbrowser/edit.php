
<?php

  include( PLUGIN_DIR.'sketchbrowser/xCompiler.php' );
  
    
  $sketch = getUrlParam('sketch');
  $do = getUrlParam('do');
    
  
  $compiler= new Compiler();

  switch ($do){
    case 'cancel': break;
    case 'save': $compiler->saveToFile( $sketch, getUrlParam('text') ); break;
    case 'compile': $compiler->compile( $sketch, getUrlParam('text') ); break;
  
  
  }
  
  $compiler->edit($sketch);
  
 
  
?>




 
