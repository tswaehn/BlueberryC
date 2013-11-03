<?php
  
  function postToMe( $action ='' ){
    $title=$GLOBALS['APP']['title'];
    $pageId=$GLOBALS['APP']['pageId'];
    
    echo '?app='.$title.'&pageId='.$pageId.'&action='.$action;
  }

  function linkToMe( $action ='' ){
    $title=$GLOBALS['APP']['title'];
    $pageId=$GLOBALS['APP']['pageId'];
    
    return '?app='.$title.'&pageId='.$pageId.'&action='.$action;
  }
    
  
  function setCaption( $caption ){
    $GLOBALS['APP']['caption'] = $caption;
  }
  
  function setDefaultPage( $page ){
    $GLOBALS['APP']['default_page'] = $page;  
  }

  function addMenuItem( $caption, $page, $action= NULL){
    $pageId=count($GLOBALS['APP']['menu'])+1;
    $GLOBALS['APP']['menu'][$pageId]=array( 'pageId'=>$pageId, 'caption'=>$caption, 'page'=>$page, 'action'=>$action );
  }
  
  
  function renderMenu(){
    
    $app=$GLOBALS['APP'];
    $title=$app['title'];
    $list=$app['menu'];
    
    echo "<div id='menu'>";
    echo "<ul>";
    foreach( $list as $item ){
      echo "<li>";
      echo '<a href="?app='.$title.'&pageId='.$item['pageId'].'&page='.$item['page'].'&action='.$item['action'].'">'.$item['caption'].'</a>';
      echo "</li>";
    }
    echo "</ul>";
    echo "</div>";
  
  }
  
 


?>
