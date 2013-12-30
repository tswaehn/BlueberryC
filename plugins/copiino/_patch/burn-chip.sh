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
hexfile=$1
toolsdir=/usr/share/arduino/hardware/tools

#start

if [ "$hexfile" == "" ]; then
	echo "no hexfile given"
	exit 1
fi

if [ "$(id -u)" != "0" ]; then
	echo "Sorry, you are not root. Start with 'sudo'."
	exit 1
fi

echo "---" >> $log
echo "$(date) - programming file $hexfile" >> $log

# now burn the hexfile
OUTPUT=`sudo avrdude -p m1284p -c linuxspi -P /dev/spidev0.0 -U flash:w:"$hexfile":i 2>&1` 

#echo -e "$OUTPUT" >> $log

# check if connection is fine
if [ $(echo -e "$OUTPUT" | grep -c "device initialized and ready") -eq 1 ]
then
	echo "connection to device established" >> $log
	echo "device connected"
else
	echo "connection do device failed" >> $log
	echo "no device found"
	echo -e "$OUTPUT" >> $log

	echo "resetting chip" >> $log
	sudo "$toolsdir/run_chip.sh"
	$(sleep 1)
	sudo "$toolsdir/reset_chip.sh"
	exit 1
fi

SIZE=`echo -e "$OUTPUT" | grep "bytes of\|Fuses"`
echo -e "$SIZE" >> $log
echo -e "$SIZE"

if [ $(echo -e "$OUTPUT" | grep -c error) -ne 0 ]
then
	echo "programming hex failed" >> $log
	echo "programming hex failed"
	echo -e "$OUTPUT" >> $log
	exit 1	
else
	echo "done" >> $log	
	echo "done"
fi

exit 0
