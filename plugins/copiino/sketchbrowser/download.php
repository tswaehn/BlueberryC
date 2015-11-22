<?php

    
    define('DEBUG', 0);
    
    define('PLUGIN_DIR', '../');

    include('../../../lib/diverse.php');
    include( PLUGIN_DIR.'sketchbrowser/xSketchConfig.php');

    $DEBUG= getUrlParam('debug');
    if (empty($DEBUG)){
        $DEBUG= DEBUG;
    }

    function out( $text ){
        global $DEBUG;
        if ($DEBUG){
            echo $text;
        }
    }

    out( "<h3>Download Sketch</h3>" );
    out( "<div id=\"sketch_browser\">" );

    out( "<pre>" );
    
    
    // get current select sketch  
    $sketch = getUrlParam('sketch');

    // load settings for sketch
    $sketchConfig= SketchConfig::loadFromSketchFolder($sketch);

    $baseDir= $sketchConfig::getSketchFolder($sketch);

    $sketchCaption= $sketchConfig->getConfig('info', 'caption');

    // create file list
    $fileList= array();

    // add INI file
    $fileList[]= 'sketch.ini';

    // add arduino sketch file
    $fileList[]= 'default.ino';

    // add cpp files
    $cppFiles= $sketchConfig->getConfig('cpp', 'file');
    foreach ($cppFiles as $cppFile ){
        $fileList[]= basename($cppFile);
    }

    // add thumbnail
    $fileList[]= basename($sketchConfig->getConfig('info', 'thumbnail'));

    // add wiring
    $fileList[]= basename($sketchConfig->getConfig('info', 'wiring'));

    // debug output
    foreach ($fileList as $file ){
        out( $file."\n" );
    }

    // compress files to archiv
    $zip = new ZipArchive();
    // setup zip name
    $filename = $sketchCaption.'__'. date("Y-M-d__h-i-sa").'.zip';
    out( "creating archive ".$filename."\n" );

    // open zip archive
    if ($zip->open($baseDir . $filename, ZipArchive::CREATE)!==TRUE) {
      exit("cannot open <$filename>\n");
    }

    foreach( $fileList as $file ){
        out( "adding file ".$file." ... " );

        if ($zip->addFile( $baseDir . $file, $file )){
            out( "ok\n" );
        } else {
            out("failed\n" );
        }
    }

    $zip->close();

    if ($DEBUG==0){
        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Length: " . filesize($baseDir . $filename));

        readfile($baseDir . $filename);
        
        // delete file
        unlink($baseDir . $filename );
    } else {
        out("download file <a href=\"".$baseDir.$filename ."\">".$filename."</a>\n" );
    }

?>