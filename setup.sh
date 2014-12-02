#!/bin/bash 
#
# author: sven.ginka@gmail.com
# date: 23.dec.2013
# modified: 29.dec.2013
#

# this script will be invoked per cmd line by
# curl http://www.copiino.cc/WebAppCenter/install.sh | bash /dev/stdin

# \todo
# 	check existence of "package.txt"

# config for WebAppCenter
install_path=/var/www/BlueberryC
log=/tmp/install-log.txt

# check last update timestamp
# stat -c %Y /var/cache/apt/

# install apache and php5
  if [ $(dpkg-query -l | grep -c '^i.* apache2 ') -eq 1 ]
  then
    echo "apache2 allready installed"
  else
    sudo apt-get update  
    sudo apt-get install -y apache2
  fi

  if [ $(dpkg-query -l | grep -c '^i.* libapache2-mod-php5 ') -eq 1 ]
  then
    echo "libapache2-mod-php5 allready installed"
  else
    sudo apt-get update  
    sudo apt-get install -y libapache2-mod-php5
  fi
  
  if [ $(dpkg-query -l | grep -c '^i.* php5-curl ') -eq 1 ]
  then
    echo "php5-curl allready installed"
  else
    sudo apt-get update  
    sudo apt-get install -y php5-curl
  fi

# install web-app-center
  echo "--- install BlueberryC "
  # setup directories
  echo "creating target directory ($install_path)"
  sudo mkdir -p $install_path
  # move over all important files
  package=$(< "package.txt")
  echo "extracting $package"
  sudo tar -xvpf $package -C $install_path >> $log
  # make them available to apache2
  sudo chown www-data.www-data $install_path -R
  
  echo "--- install socket"
  sudo cp BlueberryC-server/blueberryc-server /etc/init.d/
  sudo cp -R BlueberryC-server /usr/lib/

  ## update startup information
  sudo update-rc.d blueberryc-server defaults

  ## restart blueberryc-server socket
  sudo /etc/init.d/blueberryc-server restart
  

  # -- add www-data to sudoers - this is neccessary because we
  #    need access to reboot/shutdown/.. 
  if ! [ -e "/etc/sudoers.d/www-data" ]; then
    echo "adding www-data to sudoers"
    sudo bash -c "echo 'www-data ALL=(ALL) NOPASSWD: ALL' > /etc/sudoers.d/www-data"
  fi

  echo " --- install BlueberryC - done"
  echo " "

# install plugins 
  echo "--- install plugins"
  ./plugins/setup_plugins.sh

#finished
echo "install finished"

echo " --- "
myip=`sudo ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'`
if [ -n "$myip" ]; then
  echo "(eth0) start a browser and type http://$myip/BlueberryC/"
fi
myip=`sudo ifconfig wlan0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'`
if [ -n "$myip" ]; then
  echo "(wlan0) start a browser and type http://$myip/BlueberyC/"
fi
echo " --- "

 
 
