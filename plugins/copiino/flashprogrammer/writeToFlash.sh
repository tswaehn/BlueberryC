#!/bin/bash 

myname=$0
filename=$1

echo "current file is "$filename
sudo avrdude -c linuxspi -p m1284p -P /dev/spidev0.0 -U flash:w:"$filename":i
#ls -la
