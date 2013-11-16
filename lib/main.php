

    <?php

      function loadPage( $page ){
	
	if (is_file($page)){
	  include( $page );
	} else {
	  // please note, ... we are in the plugin folder now
	  include( '../lib/pageNotFound.php' );
	}
	  
	
      }
      
    
      if (!isset($url_pageId)){
	$pageId='';
      } else {
	$pageId=$url_pageId;
      }
    
    
      if (!isset($url_page)){
	$page='';
      } else {
	$page=$url_page;
      }

      if (!isset($url_action)){
	$action='';
      } else {
	$action=$url_action;
      }
      
      
      
      // check the array for current page and action
      $menu=$APP['menu'];
      
      // now move over to the app directory
      chdir( $APP['base_dir'] );
     
      if (array_key_exists( $pageId, $menu )){
	  $item = $menu[$pageId];
	  $APP['pageId']=$pageId;
	  
	  $page = $item['page'];


      } else {
	  //echo "-loading default-<br>";
	  $APP['pageId']='';
	  $page=$APP['default_page'];
	  
      }
      
      // now insert page
      loadPage($page);
      
    
    ?>
    