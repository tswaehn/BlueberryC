

    <?php

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
	  if (is_file($page)){
	    include($page);
	  } else {
	    echo "the desired link does not exist";
	  }
      } else {
	  echo "-no valid page found-<br>";
	  $APP['pageId']='';
	  include( $APP['default_page'] );
	  
      }
      
    
    ?>
    