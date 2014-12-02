#!/bin/bash 

myname=$0

echo "there are $# args"

sketch="$1"
file="$2"

echo "my name is $myname"
echo "I will move over to $sketch"
echo "and will compile $file"

cd "./plugins/copiino/sketches/$sketch"

#ls -la 
sudo make all

 
