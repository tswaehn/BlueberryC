#!/usr/bin/env python
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setup(25,GPIO.OUT)
GPIO.output(25,0)
GPIO.setup(24,GPIO.OUT)
GPIO.output(24,0)