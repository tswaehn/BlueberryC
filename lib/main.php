

    <?php

      function loadPage( $page ){
	
	// check if a plugin is called
	if (strpos( $page, PLUGINS ) === false ){
	  echo 'Cannot load page '.$page.'. Path specification is wrong.<br>';
	  include( './lib/pageNotFound.php' );
	  return;
	}
	
	if (is_file($page)){
	  //echo 'loading page '.$page.'<br>';
	  
	  include( $page );
	} else {

	  include( './lib/pageNotFound.php' );
	}
	  
	
      }
      
      // global vars
      $pageId = getUrlParam('pageId');
      $page = getUrlParam('page');
      $action = getUrlParam('action');

      
      // check the array for current page and action
      $menu=$APP['menu'];
      
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
    