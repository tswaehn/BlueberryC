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
    echo "<div id='menu-scroll'>";
    echo "<ul>";
    foreach( $list as $item ){
      echo "<li>";
      echo '<a href="?app='.$title.'&pageId='.$item['pageId'].'&page='.$item['page'].'&action='.$item['action'].'">'.$item['caption'].'</a>';
      echo "</li>";
    }
    echo "</ul>";
    /*
    echo "Text (von lateinisch texere: weben/flechten) bezeichnet im nichtwissenschaftlichen Sprachgebrauch eine abgegrenzte, zusammenhängende, meist schriftliche sprachliche Äußerung, im weiteren Sinne auch nicht geschriebene, aber schreibbare Sprachinformation (beispielsweise eines Liedes, Films oder einer improvisierten Theateraufführung).

Aus sprachwissenschaftlicher Sicht ist ein Text die sprachliche Form einer kommunikativen Handlung. Texte werden einerseits durch pragmatische, also situationsbezogene, „textexterne“ Merkmale, andererseits durch sprachliche, „textinterne“ Merkmale bestimmt.[1] In der Sprach- und Kommunikationswissenschaft existieren viele verschiedene Textdefinitionen nebeneinander, die anhand verschiedener Textualitätskriterien Texte und „Nicht-Texte“ voneinander trennen. Weiter gefasste Textbegriffe schließen auch Illustrationen oder Elemente der nonverbalen Kommunikation (etwa Mimik und Gestik) in den Text ein.[2] Unter Umständen kann sogar eine reine Bildsequenz als Text gelten, wenn damit erkennbar eine kommunikative Funktion erfüllt wird.[3] Der Begriff des „diskontinuierlichen“ Textes aus dem Bereich der Sprachdidaktik umfasst Texte, die nicht fortlaufend geschrieben sind und sich teilweise nicht-sprachlicher Mittel bedienen, wie Formulare, Tabellen und Listen, Grafiken und Diagramme.";
  */
    echo "</div>";
    echo "</div>";
  
  }
  
 


?>
