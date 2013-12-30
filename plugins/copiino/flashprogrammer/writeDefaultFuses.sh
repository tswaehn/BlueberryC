sudo avrdude -c linuxspi -p m1284p -P /dev/spidev0.0 -U efuse:w:0xfd:m -U hfuse:w:0x9f:m -U lfuse:w:0xff:m
