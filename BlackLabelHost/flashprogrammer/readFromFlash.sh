#!/bin/bash 

myname=$0
filename=$1
logfile="/tmp/log.txt"

sudo rm -f $logfile
echo "using file "$filename > $logfile

(sudo avrdude -c linuxspi -p m1284p -P /dev/spidev0.0 -U flash:r:$filename:i >$logfile 2>&1) &
#sudo avrdude -c linuxspi -p m1284p -P /dev/spidev0.0 -U flash:r:test.hex:i

exit 1
