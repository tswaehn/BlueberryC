#!/bin/bash 

#config
TOOLS_DIR=/usr/share/arduino/hardware/tools

#check sudo
("$TOOLS_DIR/check-sudo.sh")

#stop AVR
("$TOOLS_DIR/reset-chip.sh")

# write fuses
sudo avrdude -c linuxspi -p m1284p -P /dev/spidev0.0 -U efuse:w:0xfd:m -U hfuse:w:0x9f:m -U lfuse:w:0xff:m
#ls -la

#start AVR
("$TOOLS_DIR/run-chip.sh")


