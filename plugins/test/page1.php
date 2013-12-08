

<h3>this is the content of page1</h3>


<?php echo "current action is ".getAction()."<br>";?>

<div style="border:dashed gray;min-height:100px;">
  <?php 
      echo getUrlParam( 'text' );
    ?>  
</div>

<p>

<form action="<?php postToMe(); ?>" method="post">

<textarea name="text" cols="30" rows="10">
Here is some sample text. Post it on this page.
</textarea>

<input type="submit" name="submit" value="post">
</form>

