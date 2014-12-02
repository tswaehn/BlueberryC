

<?php

  
  include("serverconfig.php");
  include("xIniTools.php");
  include("diverse.php");
  include("xSketch.php");
  
  $style=file_get_contents( 'format.css' );
  //echo '<base href="http://homer/~tswaehn/git_dev/BlueberryC-retro/plugins/copiino/filereceive/" />';
  
  echo "<style>".$style."</style>";
  
  
  date_default_timezone_set('Europe/London');

  $sketch = getUrlParam( "sketch" );
  
  echo '<div id="box">';

  if (empty( $sketch )){
  
    renderSketchOverview();  
    
  } else {
  
    renderSingleSketch( $sketch );
    
  }
  

  echo "</div>";  

  
  echo '<p style="clear:left">';
  

  
?>
