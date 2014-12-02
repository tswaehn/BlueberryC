#!/bin/bash 
#
# author: sven.ginka@gmail.com
# date: 23.dec.2013
# modified: 29.dec.2013
#

# --- install copiino
# install arduino-core and arduino-mk
  # sudo apt-get -y install arduino-core arduino-mk

# config
# --- config 

name="rs232terminal"
packagename="plugin_"$name".tar.gz"
basedir="./plugins/rs232terminal/"


install_path="/var/www/BlueberryC/"
log="/tmp/setup-log.txt"

# start
package=$basedir$packagename
patch=$basedir"_patch/"

echo "--- setup $name"

# extract 
  echo "extracting $package to $install_path"
  sudo tar -xvpf $package -C $install_path >> $log
  # make them available to apache2
  sudo chown www-data.www-data $install_path -R

# apply the patch

  echo "distributing files"
  # move files into target dir

  sudo cp -f $patch"remserial" /usr/bin/
  sudo cp -f $patch"rpi-serial-console" /usr/bin
  sudo cp -f $patch"remseriald" /etc/init.d/
  
  ## update startup information
  sudo update-rc.d remseriald defaults

  ## restart remserial socket
  sudo /etc/init.d/remseriald restart
  
  ## make sure the SPI module is loaded (needed for $name)
  if [ $(sudo rpi-serial-console status | grep -c "enabled") -eq 1 ]; then
    echo "disabling console..."
    # disable the serial console
    rpi-serial-console disable
    echo "important: please reboot raspi to make the changes take effect!"
    
  else
    echo "console already disabled"
  fi


## clear build folders

# finished
echo "done" > $log

echo " --- setup $name - done"
 
