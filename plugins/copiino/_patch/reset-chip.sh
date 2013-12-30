#!/usr/bin/env python
import time
import RPi.GPIO as GPIO
print "resetting chip..."
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(25,GPIO.OUT)
# set reset to "1"
GPIO.output(25,1)
# set CS to "1"
GPIO.setup(24,GPIO.OUT)
GPIO.output(24,1)
time.sleep(0.1)
# enable CS
GPIO.output(24,0)
# tie down reset
GPIO.output(25,0)
time.sleep(0.1)
GPIO.output(25,1)
time.sleep(0.1)
GPIO.output(25,0)
print "done"
