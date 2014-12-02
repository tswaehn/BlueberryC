#!/bin/bash 
#
# author: sven.ginka@gmail.com
# date: 23.dec.2013
# modified: 18.jan.2014
#
# invoke like:
#	./_create_plugins.sh 
#

# --- config 
basedir='./plugins/'

# --- start
$basedir"copiino/_create_plugin.sh"
$basedir"rs232terminal/_create_plugin.sh"

# done
exit 0