<html>

<head>
<link rel="stylesheet" type="text/css" href="format.css">
</head>

<body>
  
    
    <div id="header"><?php include('header.php'); ?></div>

    <div id="menu">
      <table>
      <tr>
	<td><a href="?page=home">home</a></td>
	<td><a href="?page=sketch">sketch</a></td>
        <td><a href="?page=prog">flashprogrammer</a></td>
	<td><a href="?page=controls">controls</a></td>
	<td><a href="?page=about">about</a></td>
	</tr>
      </table>
    
    </div>

    <div id="content">
      <?php
	extract( $_GET, EXTR_PREFIX_ALL, "url" );
	extract( $_POST, EXTR_PREFIX_ALL, "url" );
	if (!isset($url_page)){
	  $page='';
	} else {
	  $page=$url_page;
	}
	
	switch ($page) {
	    case "home": include( './home/index.php'); break;
	    case "sketch": include( './sketcheditor/index.php'); break;
	    case "prog": include( './flashprogrammer/index.php'); break;
	    case "controls": include( './controls/index.php' );break;
	    case "about": include( './about/index.php' );break;
	  
	    default: echo "select a page";
	  }
	
      
      ?>
    
    </div>
 

  
</body>
</html>