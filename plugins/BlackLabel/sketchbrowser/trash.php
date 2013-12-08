
<?php
    
  include('./sketchbrowser/xBrowser.php');
  
  $browser = new Browser();

  // check if something needs to be done before browsing
  $do = getUrlParam('do');
  switch ($do){
    case 'restore': $browser->restoreSketch( getUrlParam('sketch') ); break;
    case 'kill': $browser->killSketch( getUrlParam('sketch') ); break;
  }
    
?>

<h3>Sketch - Trash</h3>
<div id="sketch_browser">
  
  <?php $browser->displayTrash() ?>
  
</div>



