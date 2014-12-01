#!/bin/bash 

#config
myname=$0
filename=$1
TOOLS_DIR=/usr/share/arduino/hardware/tools

#check sudo
("$TOOLS_DIR/check-sudo.sh")

#stop AVR
("$TOOLS_DIR/reset-chip.sh")

# write data
echo "burning data to chip"
echo "filename is "$filename
sudo avrdude -c linuxspi -p m1284p -P /dev/spidev0.0 -U flash:w:"$filename":i
#ls -la

#start AVR
("$TOOLS_DIR/run-chip.sh")