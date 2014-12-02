#!/bin/bash 

# --- config 

name="copiino"
packagename="plugin_"$name".tar.gz"
setupname="plugin_setup"".tar.gz"
basedir="./plugins/copiino/"
patch="_patch/"

# --- start
echo "--- creating copiino plugin"
echo "target is $setupname ($packagename)"

package=$basedir$packagename
setup_package=$basedir$setupname

filelist=$basedir"plugin_list.txt"
setuplist=$basedir"plugin_setup_list.txt"

rm -f $filelist
rm -f $setuplist

# create package
  find $basedir  -maxdepth 1 -iname "*.php" -print >> $filelist
  find $basedir"avrcontrols"  -iname "*" -print >> $filelist
  find $basedir"flashprogrammer"  -iname "*" -print >> $filelist
  find $basedir"sketchbrowser"  -iname "*" -print >> $filelist
  echo $basedir"sketches/" >> $filelist
  echo $basedir"trash/" >> $filelist
  find $basedir"sketches/blinky"  -iname "*" -print >> $filelist
  

  # create archive (note that www-data is usually uid=33 gid=33, thus change owner now)
  tar -cpzf $package --owner=33 --group=33 -T $filelist

#create setup
  echo $basedir$patch"avrdude" >> $setuplist
  echo $basedir$patch"libelf.so.1" >> $setuplist  
  echo $basedir$patch"avrdude.conf" >> $setuplist
  echo $basedir$patch"boards.txt" >> $setuplist
  echo $basedir$patch"pins_arduino.h" >> $setuplist
  
  echo $basedir$patch"burn-chip.sh" >> $setuplist
  echo $basedir$patch"check-fuses.sh" >> $setuplist  
  echo $basedir$patch"check-sudo.sh" >> $setuplist  
  echo $basedir$patch"reset-chip.sh" >> $setuplist  
  echo $basedir$patch"run-chip.sh" >> $setuplist
  echo $basedir$patch"enable-rs232.sh" >> $setuplist
  echo $basedir$patch"disable-rs232.sh" >> $setuplist
  
  echo $basedir$patch"copiinod" >> $setuplist

  # finally the default plugin setup script
  echo $basedir"setup_plugin.sh" >> $setuplist
  #
  echo $package >> $setuplist
  
  # create the setup archive (thread as root package, thus chane owner to uid=0, gid=0)
  tar -cpzf $setup_package --owner=0 --group=0 -T $setuplist


#done
exit 0 
 
