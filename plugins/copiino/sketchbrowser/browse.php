
<?php
    
  include( PLUGIN_DIR.'sketchbrowser/xBrowser.php');
  
  $browser = new Browser();

  // check if something needs to be done before browsing
  $do = getUrlParam('do');
  switch ($do){
    case 'new': $browser->newSketch( getUrlParam('sketch') ); break;
    case 'del': $browser->removeSketch( getUrlParam('sketch') ); break;
  }
    
?>

<h3>Sketches</h3>
<div id="sketch_browser">
  
  <?php $browser->display() ?>
  
</div>



<p>
<h3>Create a new Sketch</h3>

<form action="<?php postToMe('browse'); ?>&do=new" method="post">
<label for="name">Enter name for new sketch</label>
<input type="text" name="sketch" value="" />
<br>
<input type="submit" name="submit" value="Submit">
</form>


<p>

<h3>Upload a new Sketch</h3>
Upload a new File:
<form action="<?php postToMe('browse'); ?>&do=upload" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file"><br>
<input type="submit" name="submit" value="Submit">
</form>

 
<p>
<h3>Removed Sketches</h3>
You find all deleted files in <a href="<?php postToMe('trash'); ?>">trash</a>

<p>
