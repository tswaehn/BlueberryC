#!/bin/bash 

timeout=10
i=0

  echo "arguments passed through $@"

  while test $i -ne $timeout; do
  
    echo "waiting ... " $i
  
    i=`expr $i + 1`
    sleep 1
    
  done
  