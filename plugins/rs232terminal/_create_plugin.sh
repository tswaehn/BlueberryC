#!/bin/bash 

# --- config 

name="rs232terminal"
packagename="plugin_"$name".tar.gz"
setupname="plugin_setup"".tar.gz"
basedir="./plugins/rs232terminal/"
patch="_patch/"

# --- start
echo "--- creating $name plugin"
echo "target is $setupname ($packagename)"

package=$basedir$packagename
setup_package=$basedir$setupname

filelist=$basedir"plugin_list.txt"
setuplist=$basedir"plugin_setup_list.txt"

rm -f $filelist
rm -f $setuplist

# create package
  find $basedir  -maxdepth 1 -iname "*.php" -print >> $filelist
  find $basedir  -maxdepth 1 -iname "*.png" -print >> $filelist  
  find $basedir  -maxdepth 1 -iname "*.js" -print >> $filelist  
  
  # create archive (note that www-data is usually uid=33 gid=33, thus change owner now)
  tar -cpzf $package --owner=33 --group=33 -T $filelist

#create setup
  echo $basedir$patch"remserial" >> $setuplist
  echo $basedir$patch"remseriald" >> $setuplist  
  echo $basedir$patch"rpi-serial-console" >> $setuplist
  #
  find $basedir$patch"remserial.src"  -iname "*" -print >> $setuplist

  # finally the default plugin setup script
  echo $basedir"setup_plugin.sh" >> $setuplist
  #
  echo $package >> $setuplist
  
  # create the setup archive (thread as root package, thus change owner to uid=0, gid=0)
  tar -cpzf $setup_package --owner=0 --group=0 -T $setuplist


#done
exit 0 
 
