#!/usr/bin/env python
import time
import RPi.GPIO as GPIO
print "starting chip..."
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(24,GPIO.OUT)
# set CS to low (as it allready is)
GPIO.output(24,0)
GPIO.setup(25,GPIO.OUT)
# set reset to low
GPIO.output(25,0)
time.sleep(0.1)
# release reset
GPIO.output(25,1)
time.sleep(0.1)
GPIO.output(25,0)
time.sleep(0.1)
GPIO.output(25,1)
# set CS to high
GPIO.output(24,1)
print "done"
