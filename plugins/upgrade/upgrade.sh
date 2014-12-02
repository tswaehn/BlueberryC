#!/bin/bash 
#
# author: sven.ginka@gmail.com
# date: 23.dec.2013
# modified: 29.dec.2013
#

# this script will be invoked per cmd line by
# curl http://www.copiino.cc/WebAppCenter/install.sh | bash /dev/stdin
# curl http://www.copiino.cc/WebAppCenter/rc/install.sh | bash -s rc

# get install type
dl_file=$1
dl_name=$2

echo "source is $dl_file"
echo "name is $dl_name"

# create and move to a save place (tempfolder)
cd /tmp/
tempfolder="/tmp/webappcenter/"
sudo mkdir -p $tempfolder
cd $tempfolder
# cleanup first
sudo rm $tempfolder"/*" -r -f

# set default variables
local_file=$tempfolder$dl_name
log=$tempfolder"install-log.txt"
setup=$tempfolder"setup.sh"

# start download setup package
echo "downloading install content ... "
curl -s $dl_file -o $local_file

echo "extracting ..."
sudo tar -xvpf $local_file > $log

if [ -e $setup ]; then
  echo "starting setup"
  $setup
  exit 0
  
else
  echo "error: setup file missing"
  exit 1
fi

