
<?php


?>

Download complete. Please set filename:
<p>
<form action="?page=prog&flashstate=read_move" method="post">
<table><tr><td>Filename</td><td>
<?php
  echo '<input type="text" name="file" size="40" value="downloaded_'.date().'_'.time().'.hex">';
?>
</td></tr></table>

<p>
Your file will be added to the local files storage.
<p>
<input type="submit" name="submit" value="Done">
</form>

