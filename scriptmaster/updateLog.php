
<?php
  extract( $_GET, EXTR_PREFIX_ALL, "url" );
  extract( $_POST, EXTR_PREFIX_ALL, "url" );

  exec('./getProcessData.sh', $output, $retVal );

  foreach ($output as $line){
    echo $line."<br>";
  }

  switch ($retVal){
    case 0: echo "script done<br>";break;
    case 1: echo "script exececuting<br>";break;
    case 127: echo "cannot run script<br>";break;
    default:
	echo "return ".$retVal."<br>";
  }


// depending on the return state
if ($retVal == 0){
  $enable='';
} else {
  $enable='disabled';
}

  if (!isset($url_pageId)){
    $pageId='';
  } else {
    $pageId=$url_pageId;
  }

  if (!isset($url_action)){
    $action='';
  } else {
    $action=$url_action;
  }
  
  if (!isset($url_app)){
    $app='';
  } else {
    $app=$url_app;
  }
  
echo $app.' '.$pageId.' '.$action.'<br>';  
echo '<form action="?app='.$app.'&pageId='.$pageId.'&action='.$action.'" method="post">';
echo '<input type="submit" name="submit" value="Done" '.$enable.'>';
echo '</form>';

?>
 

<p>



</form>
