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
packagename="plugin_copiino.tar.gz"
basedir="./plugins/copiino/"
install_path="/var/www/BlueberryC/"
log="/tmp/setup-log.txt"

# start
package=$basedir$packagename
patch=$basedir"_patch/"

echo "--- setup copiino"

# extract 
  echo "extracting $package to $install_path"
  sudo tar -xvpf $package -C $install_path >> $log
  # make them available to apache2
  sudo chown www-data.www-data $install_path -R

# apply the patch

  echo "distributing files"
  # move files into target dir
  target=/usr/share/arduino/hardware/tools/
  sudo mkdir -p $target
  sudo cp -f $patch"boards.txt" $target
  sudo cp -f $patch"burn-chip.sh" $target
  sudo cp -f $patch"check-fuses.sh" $target
  sudo cp -f $patch"check-sudo.sh" $target
  sudo cp -f $patch"reset-chip.sh" $target
  sudo cp -f $patch"run-chip.sh" $target

  variants=/usr/share/arduino/hardware/arduino/variants/copiino/
  sudo mkdir -p $variants
  sudo cp -f $patch"pins_arduino.h" $variants
  #patch avrdude
  sudo cp -f $patch"avrdude.conf" /etc/
  sudo cp -f $patch"avrdude" /usr/bin/
  sudo cp -f $patch"libelf.so.1" /usr/lib/
  if [ ! -e "/usr/local/etc/avrdude.conf" ]; then
    sudo ln -s /etc/avrdude.conf /usr/local/etc/avrdude.conf
  fi


## make sure the SPI module is loaded (needed for copiino)
if [ $(sudo lsmod | grep -c "spi_bcm2708") -eq 0 ]; then
  echo "enabling SPI driver..."
  sudo modprobe -i spi-bcm2708
  sudo sed -i 's/blacklist spi-bcm2708/#blacklist spi-bcm2708/g' /etc/modprobe.d/raspi-blacklist.conf
fi

## clear build folders

# finished
echo "done" > $log

echo " --- setup copiino - done"
 
