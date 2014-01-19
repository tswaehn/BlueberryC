PORT=10000
DEV=/dev/ttyAMA0
TTY="115200,raw"

OPTS=" -d -p $PORT -m 1 -x 2 $DEV"
sudo ./remserial $OPTS -s "115200 raw"
