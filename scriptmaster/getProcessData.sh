#!/bin/bash 

#get info about the script itself
me=$0
path=$(dirname $me)

lockFile="/tmp/process.pid"
logFile="/tmp/log.txt"
cmdFile="/tmp/cmd.txt"
internFile="/tmp/any.log"

#redirect anything to log
debug=0
if [ $debug -eq 0 ]
then
  exec 3>&1 >&$internFile
  else
  exec 3>&1
  echo "--debug on--"
fi

#display
echo "I am $me"
echo "and live here $path"
echo "I will execute $cmd"

processActive=0
if test -f $lockFile 
then
  echo "lockfile exists $lockFile"
  
  pid=$(cat $lockFile)
  
  echo "pid is $pid"
  
  if sudo test -f /proc/$pid/exe
  then
  echo "process is running"
  processActive=1
  else
  echo "process is stopped"
  fi
  
else
  
  echo "lockfile does not exist"
fi

cmd=$(cat $cmdFile)
printf "running currently $cmd\r\n"

text=$(cat $logFile)
printf "start output:\r\n---\r\n"
printf "$text\r\n" >&3
printf "---\r\ndone output"

exit $processActive