<html>
<head>
<link rel="stylesheet" type="text/css" href="format.css">
<link rel="stylesheet" type="text/css" href="apps.css">
<link rel="stylesheet" type="text/css" href="plugins.css">

<?php 
    include('./lib/diverse.php');
    include('./lib/plugin.php');  
    include('./scriptmaster/scriptmaster.php');
    include('./lib/js_update.php');
    ?>

<title>
..::BlueberryControl::.. - <?php echo $APP['title']; ?>
</title>
</head>

  <div id="frame">
  
    <div id="apps">
      <div id="apps2">
      <?php include('./lib/nav.php'); ?>
      </div>
    </div>

    <div id="main">
	<div id="header">
	  <?php include('./lib/title.php'); ?>
	  <?php renderMenu(); ?>
	</div>
	
	<div id="content">
	  <?php include( './lib/main.php' ); ?>
	</div>
    </div>

  </div>
</html>