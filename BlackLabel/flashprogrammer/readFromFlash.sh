#!/bin/bash 

myname=$0
filename=$1

echo "filename is "$filename
sudo avrdude -c linuxspi -p m1284p -P /dev/spidev0.0 -U flash:r:$filename:i
#ls -la


