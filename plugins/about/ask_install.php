<h3>install</h3>
 
<?php

  $ver=getUrlParam('ver');
  $file=getUrlParam('file');
  $link=getUrlParam('link');

  echo 'Your software will be upgraded<br>';
  echo 'The file';
  echo '<ul>';
  echo '<li>'.$file.' ('.$ver.')</li>';
  echo '</ul>';
  
  echo 'from ';
  echo '<ul>';
  echo '<li>'.$link.'</li>';
  echo '</ul>';
  
  echo 'will be installed.';

  echo '<p>';
  echo 'Are you sure you want to upgrade now?';
  
  //echo 'Yes please <a href="'.linkToMe('ask_install').'&file='.$file.'&ver='.$ver.'&link='.$link.'">continue</a> and install the new version!';
    
  //PostToMe('ask_install','&file='.$file.'&ver='.$ver.'&link='.$link.' );

  echo '<form action="'.linkToMe('X').'&file='.$file.'&ver='.$ver.'&link='.$link.'" method="post">';
  echo '<button type="submit" name="action" value="cancel">Cancel</button>';
  echo '<button type="submit" name="action" value="install">Start</button>';
  echo '</form>';   
  

?> 
