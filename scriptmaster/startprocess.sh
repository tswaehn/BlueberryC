#!/bin/bash 

#get info about the script itself
me=$0
cmd=$1
path=$(dirname $me)

#remove first argument and pass through remaining
shift


# import all arguments and preserve white spaces
args=()
i=0
for f in "$@"; do 
  args[$i]="$f"
  (( ++i ))
done


#config
logFile="/tmp/log.txt"
lockFile="/tmp/process.pid"
cmdFile="/tmp/cmd.txt"
internFile="/tmp/any.log"

#redirect anything to log
debug=0
if [ $debug -eq 0 ]
then
  exec &>$internFile
  else
  echo "--debug on--"
fi

#display some information about me
echo "I am $me"$($out)
echo "and live here $path"$out
echo "I will execute $cmd"$out
echo "I will pass-through $# args: ${args[@]} "$out

#check if there is allready a lockFile and get the PID
processActive=0
if test -f $lockFile 
then
  echo "lockfile exists $lockFile"
  
  pid=$(cat $lockFile)
  
  echo "last pid is $pid"
  
  #now check if the process is still running
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

#depending on process beeing active
if [ $processActive -eq 0 ]
then
  echo "starting process"
  
  #save to file what we are doing right now
  echo $cmd > $cmdFile
  
  #remove the logfile
  sudo rm -f $logFile
  echo "removed file logFile"
  date > $logFile

  #start the process itself in a child-proces, while logging all information to the logfile
  (sudo $cmd "${args[@]}" > $logFile  2>&1)&

  #store the process id (PID)
  echo $! > $lockFile

  #show the current PID
  echo "my PID is $(cat $lockFile)"
  
  #everything is fine
  exit 0
  
else
  echo "will not restart process"
  exit 1
fi
  