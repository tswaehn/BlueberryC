#!/usr/bin/env python
import time
import RPi.GPIO as GPIO
print "enabling rs232..."
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(18,GPIO.OUT)
# set CS from rs232 to low
GPIO.output(18,0)
print "done"
