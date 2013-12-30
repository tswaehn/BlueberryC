#!/bin/bash 
#
# author: sven.ginka@gmail.com
# date: 21.dec.2013
#
# description:  just check if we have hardware access	
#


#start

if [ "$(id -u)" != "0" ]; then
	echo "Sorry, you are not root. Start with 'sudo'."
	exit 1
fi
exit 0
