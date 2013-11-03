<html>
<head>
<link rel="stylesheet" type="text/css" href="format.css">

<?php 
    include('./lib/plugin.php');  
    include('./scriptmaster/scriptmaster.php');
    ?>

<title>
..::WebAppCenter::.. - <?php echo $APP['title']; ?>
</title>
</head>

  <div id="frame">
  
    <div id="apps">
      <div id="apps2">
      <?php include('nav.php'); ?>
      </div>
    </div>

    <div id="main">
	<div id="header">
	  <?php include('title.php'); ?>
	  <?php renderMenu(); ?>
	</div>
	
	<div id="content">
	  <div id="debugx">
	  <?php print_r($APP);?>
	  </div>
	  <?php include( './lib/main.php' ); ?>
	</div>
    </div>

  </div>
</html>