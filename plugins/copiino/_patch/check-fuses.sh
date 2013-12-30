#!/bin/bash 
#
# author: sven.ginka@gmail.com
# date: 21.dec.2013
#
# description: 	this script checks the fuses of our board
#		if set incorrectly it burns the fuses as expected
#

#config
log=/var/log/copiino.log
toolsdir=/usr/share/arduino/hardware/tools

hfuse=0x9f
lfuse=0xff
efuse=0xfd
#efuse=0xfd 

#hfuse=0x10

#start

if [ "$(id -u)" != "0" ]; then
	echo "Sorry, you are not root. Start with 'sudo'."
	exit 1
fi

echo "---" >> $log
echo "$(date) - check fuses" >> $log

OUTPUT=`sudo avrdude -p m1284p -c linuxspi -P /dev/spidev0.0 \
	-U efuse:v:$efuse:m \
	-U hfuse:v:$hfuse:m \
	-U lfuse:v:$lfuse:m 2>&1` 

echo -e "$OUTPUT" >> $log

# check if connection is fine
if [ $(echo -e "$OUTPUT" | grep -c "device initialized and ready") -eq 1 ]
then
	echo "connection to device established" >> $log
	echo "device connected"
else
	echo "connection do device failed" >> $log
	echo "no device found"
	echo -e "$OUTPUT" >> $log
	echo "re-resetting chip" >> $log
	sudo "$toolsdir/run-chip.sh" 
	sleep 1
	sudo "$toolsdir/reset-chip.sh"
	exit 1
fi

if [ $(echo -e "$OUTPUT" | grep -c error) -ne 0 ]
then
	echo -e "$OUTPUT" >> $log	
	echo "reprogramming fuse" >> $log
	echo "reprogramming fuse"

	OUTPUT=`sudo avrdude -p m1284p -c linuxspi -P /dev/spidev0.0 \
	        -U efuse:w:$efuse:m \
       	 	-U hfuse:w:$hfuse:m \
        	-U lfuse:w:$lfuse:m 2>&1`
	
	echo -e "$OUTPUT" >> $log
	echo "burned new fuse bytes"

else
	echo "fuses ok" >> $log
	echo "fuses ok"
fi

exit 0
