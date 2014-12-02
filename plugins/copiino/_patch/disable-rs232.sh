#!/usr/bin/env python
import time
import RPi.GPIO as GPIO
print "disabling rs232..."
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)
GPIO.setup(18,GPIO.OUT)
# set CS from rs232 to high
GPIO.output(18,1)
print "done"