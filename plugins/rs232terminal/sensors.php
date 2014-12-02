<?php

  //include( PLUGIN_DIR.'js_update.php' );
  
  echo "<h3>sensors</h3>";

  echo '<pre id="log">';
  echo 'empty';
  echo '</pre>';
  
  insertUpdateScript( PLUGIN_DIR.'updateTerminal.php?lines=200', 'log', "plot");


  //echo '<object data="'.PLUGIN_DIR.'graph.php" width="600" height="300" type="image/svg+xml" />';
/*
  echo '<svg id="graph" width="600" height="300"></svg>';
  
  insertUpdateScript( PLUGIN_DIR.'graph.php', 'graph');
  */
  

  echo '<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="./3rdParty/jqplot/excanvas.js"></script><![endif]-->';
  echo '<script language="javascript" type="text/javascript" src="./3rdParty/jqplot/jquery.min.js"></script>';
  echo '<script language="javascript" type="text/javascript" src="./3rdParty/jqplot/jquery.jqplot.min.js"></script>';
  echo '<link rel="stylesheet" type="text/css" href="./3rdParty/jqplot/jquery.jqplot.css" />';
  
  // the plot container
  echo '<div id="chartdiv" style="height:400px;width:600px; "></div>';
  
  
  // plot options
  $options= "{ 
        title:'Sensor Chart', 
        axes:{yaxis:{min:-100, max:100}}, 
        seriesDefaults:{showMarker:false, lineWidth:2, lineShadow:false },
        series:[{color:'#5FAB78' }]
        }";
 // $options= "";

        
  // create a chart
  echo '<script>';
  
  echo '
        MAX_INDEX=200;
        CHANNEL_COUNT= 3;
        
        
        var dataArray=null;
        var dataIndex=null;
        
        var text= "empty";
        var chart= 5;
        var values= new Array();
        
        function createDataArray(){
          dataArray= new Array( );
          dataIndex= new Array( );
          
          var i=0;
          var k=0;
          
          for (k=0; k<CHANNEL_COUNT; k++){
            dataArray.push( new Array(MAX_INDEX)); 
            dataIndex.push( 0 );
          }
          
          for (k=0; k<CHANNEL_COUNT; k++){
            
            for (i=0; i<MAX_INDEX; i++ ){
              dataArray[k][i]=  new Array(i, 0) ;
            }
          }
          
        }
        
        function resetDataArray(){
          if (dataArray== null){
            createDataArray();
          }
        
          for (k=0; k<CHANNEL_COUNT; k++){
            dataIndex[k]= 0; 
          }
        }

        function addData( channel, value ){
          if (dataArray== null){
            createDataArray();
          }
          
          if (dataIndex[channel] < MAX_INDEX){
            dataArray[channel][dataIndex[channel]++][1]= value;
          }
            
        }
        
        function plot( responseText ){
          var lines= responseText.split("\n");
          var count= lines.length;
          var i;
          
          values= new Array();
          values.push( count );
          
          resetDataArray();
          
          for (i=0; i<count; i++){
            line= lines[i];

            if (line.search("::") != -1 ){
              values.push( "in:"+line );
              tokens= line.split(" ");

              document.getElementById( "log" ).innerHTML=tokens[1]+" "+tokens[2]+" "+tokens[3];
  
              //addData( 0, Math.floor((Math.random() * 180) -90)  );
              //addData( 1, Math.floor((Math.random() * 180) -90)  );
              addData( 0, tokens[1] );
              addData( 1, tokens[2] );
              addData( 2, tokens[3] );              
              //values.push( new Array(i, tokens[1]) );

            } else {
              values.push( "ign:"+ line );
            }
          }
          
          //text=values.join("\n");          
          //document.getElementById( "log" ).innerHTML=tokens;
          
          // create graph
          //document.getElementById( "chartdiv" ).innerHTML="Waiting for data";
          
          
          if (chart==5){
            chart= $.jqplot("chartdiv",  dataArray , '.$options.');
          } else {
            //document.getElementById( "chartdiv" ).innerHTML="Waiting for data";
            opt= { data: dataArray };
            chart.replot( opt );
            //chart.quickInit();
            
          }
          
        }
        
        
        ';
  /*      
  echo 'function plot( responseText ){
          //$.jqplot("chartdiv",  [[[1, 2],[3,5.12],[5,13.1],[7,33.6],[9,85.9],[11,219.9]]], '.$options.');
        }';
    */    
  echo '</script>';
  
  
  
?>
