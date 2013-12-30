#!/bin/bash 
#
# author: sven.ginka@gmail.com
# date: 23.dec.2013
# modified: 29.dec.2013
#

# import build_nr
build_nr=`cat build_nr`

# notes on build_nr:
#
# - the build_nr controls which actions will take place
# 
# if build_nr contains "rel" then 
# 	(1) the new package will be pushed with version number to server
#	(2) the default package will be overwritten and published
#	(3) the install scripts will be updated
#	(4) the release-list on the server will be updated
#
# if build_nr contains "rc" then
#	(1) the server path is ./rc/ 
#	(2) the new package will be pushed with version number to server
#	(3) the install scripts will be updated
#	(4) the rc-release-list on the server will be updated
#
# else
#	(1) the server path is ./testing/
#	(2) the package will be pushed into a separate directory called "testing"
#	(3) the install scripts will be updated
#	(4) the test-release-list on the server will be updated
#
#


# \todo
# - hardcoded "filelist.txt"
# - hardcoded "package.txt"


# --- config 
name="BlueberryC"

ftproot="ftp://copiino.cc/BlueberryC/"
ftpuser="master:2w9pnyW1"

# --- start
packagedir="./_release/"
install_script="install.sh"

# check if "release", "release candidate" or "testing"
release=0
release_candidate=0
if [ $(echo -e "$build_nr" | grep -c "rel") -eq 1 ]
then
  echo "creating release"
  release=1
  ftpdir=$ftproot
else
  if [ $(echo -e "$build_nr" | grep -c "rc") -eq 1 ]
  then
    echo "creating release candidate"
    release_candidate=1
    ftpdir=$ftproot"rc/"
    packagedir="./_rc/"
  else
    echo "creating testing"
    ftpdir=$ftproot"testing/"
    packagedir="./_testing/"
  fi
fi

# setup filenames
packagename=$name"_"$build_nr".tar.gz"
defaultpackagename=$name".tar.gz"
setupname=$name"_"$build_nr"_setup.tar.gz"
defaultsetupname=$name"_setup.tar.gz"

# setup directories
filelist=$packagedir"filelist.txt"
setuplist=$packagedir"setuplist.txt"
packagelist=$packagedir"packages.txt"
#
package=$packagedir$packagename
default_package=$packagedir$defaultpackagename
#
setup_package=$packagedir$setupname
default_setup=$packagedir$defaultsetupname


# display recipe info
echo "all files go into $packagedir"

# create directory if not existent
mkdir -p $packagedir

# --- create core package
  echo "--- creating package"
  echo $packagename" ("$defaultpackagename")"

  # remove old list
  rm -f $filelist

  find ./  -maxdepth 1 -iname "upgrade.sh" -print >> $filelist

  # core
  find ./  -maxdepth 1 -iname "*.png" -print >> $filelist
  find ./  -maxdepth 1 -iname "*.css" -print >> $filelist
  find ./  -maxdepth 1 -iname "*.php" -print >> $filelist
  find ./  -maxdepth 1 -iname "build_nr" -print >> $filelist

  find ./lib/ -iname "*" -print >> $filelist
  find ./res/ -iname "*" -print >> $filelist
  find ./screenshots/ -iname "*" -print >> $filelist
  find ./scriptmaster/ -iname "*" -print >> $filelist

  # include only default plugins !!
  find ./plugins/about/ -iname "*" -print >> $filelist
  find ./plugins/controls/ -iname "*" -print >> $filelist
  find ./plugins/home/ -iname "*" -print >> $filelist 
  find ./plugins/missing/ -iname "*" -print >> $filelist
  find ./plugins/test/ -iname "*" -print >> $filelist


  # create archive (note that www-data is usually uid=33 gid=33, thus change owner now)
  tar -cpzf $package --owner=33 --group=33 -T $filelist

# --- create plugins
  echo "--- creating plugins"
  ./plugins/_create_plugins.sh 


# --- create setup package
  echo "--- creating setup package "
  echo "$setupname ($defaultsetupname)"

  # cleanup first
  rm $setuplist -r -f

  # include the filelist itself
  find $packagedir -maxdepth 1 -iname "filelist.txt" -print >> $setuplist
  # include the package
  find $packagedir -maxdepth 1 -iname $packagename -print >> $setuplist
  # include path to package
  echo "$package" > package.txt
  find ./ -maxdepth 1 -iname "package.txt" -print >> $setuplist
  # include the setup script
  find ./  -maxdepth 1 -iname "setup.sh" -print >> $setuplist
  # include the setup-plugins script
  find ./plugins/  -maxdepth 1 -iname "setup_plugins.sh" -print >> $setuplist

  # include plugins 
  echo "./plugins/copiino/plugin_setup.tar.gz" >> $setuplist
  
  # create the setup archive (thread as root package, thus chane owner to uid=0, gid=0)
  tar -cpzf $setup_package --owner=0 --group=0 -T $setuplist

# --- upload files to server  
  echo "--- uploading files to server"
  echo "remote target is "$ftpdir
  # move install-scripts to server
    curl -P - -T $install_script $ftpdir --ftp-create-dirs --user $ftpuser

  #move packages (raw + setup) to server
    echo "uploading new package"
    curl -P - -T $package $ftpdir --ftp-create-dirs --user $ftpuser
    curl -P - -T $setup_package $ftpdir --ftp-create-dirs --user $ftpuser  

  # update and upload package list
    echo "adding new release to download list"
    echo -e "$build_nr\t$setupname" >> $packagelist
    curl -P - -T $packagelist $ftpdir --ftp-create-dirs --user $ftpuser
    
  # overwrite default package
    echo "uploading new default package"
    cp $package $default_package
    curl -P - -T $default_package $ftpdir --user $ftpuser
    echo "uploading new default setup package"
    cp $setup_package $default_setup
    curl -P - -T $default_setup $ftpdir --user $ftpuser



# everything is fine
exit 0

