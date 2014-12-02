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
type=$1

# config for WebAppCenter
dl_root="http://www.copiino.cc/WebAppCenter/"
dl_name="WebAppCenter_setup.tar.gz"

# type selector
  if [ -z $type ] || [ $(echo -e "$type" | grep -c "rel") -eq 1 ]
  then
    echo "install release"
    dl_file="$dl_root$dl_name"
  else
    if [ $(echo -e "$type" | grep -c "rc") -eq 1 ]
    then
      echo "install release candidate"
      dl_file=$dl_root"rc/"$dl_name
    else
      echo "install testing"
      dl_file=$dl_root"testing/"$dl_name
    fi
  fi

# create and move to a save place (tempfolder)
cd /tmp/
tempfolder="/tmp/webappcenter/"
mkdir -p $tempfolder
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

