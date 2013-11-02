#!/bin/bash 

myname=$0
logfile="/tmp/log.txt"

sudo rm -f $logfile

cd ./sketcheditor/sketches/

ls -l > $logfile
(sudo make upload > $logfile)&
xit 1
 
