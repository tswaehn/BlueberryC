<?php

  include( PLUGIN_DIR.'js_update.php' );
  
  echo "<h3>sensors</h3>";

  /*
  echo '<pre id="log">';
  echo 'empty';
  echo '</pre>';
  
  insertUpdateScript( PLUGIN_DIR.'graph.php', 'log');
  */


  //echo '<object data="'.PLUGIN_DIR.'graph.php" width="600" height="300" type="image/svg+xml" />';
  echo '<svg id="graph" width="600" height="300"></svg>';
  
  insertUpdateScript( PLUGIN_DIR.'graph.php', 'graph');
  
  
?>
