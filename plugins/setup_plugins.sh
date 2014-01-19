#!/bin/bash 
#
# author: sven.ginka@gmail.com
# date: 23.dec.2013
# modified: 18.jan.2014
#


# --- install copiino
# install arduino-core and arduino-mk
sudo apt-get -y install arduino-core arduino-mk


# --- config 
basedir="./plugins/"


# --- start
  
  name="copiino"
  package=$basedir$name"/plugin_setup.tar.gz"
  # unpack plugin
  echo "extracting $name ..."
  sudo tar -xvpf $package
  # execute setup
  sudo $basedir$name"/setup_plugin.sh"

  name="rs232terminal"
  package=$basedir$name"/plugin_setup.tar.gz"
  # unpack plugin
  echo "extracting $name ..."
  sudo tar -xvpf $package
  # execute setup
  sudo $basedir$name"/setup_plugin.sh"
  
# done
exit 0
