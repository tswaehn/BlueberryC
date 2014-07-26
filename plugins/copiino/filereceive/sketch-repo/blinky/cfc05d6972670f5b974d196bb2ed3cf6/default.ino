/*
  Blink
  Turns on an LED on for one second, then off for one second, repeatedly.
 
  This example code is in the public domain.
 */

#include "test.h"

// Pin 13 has an LED connected on most Arduino boards.
// give it a name:
int led0 = 13;
int led1 = 21;
int led2 = 20;

// the setup routine runs once when you press reset:
void setup() {                
  // initialize the digital pin as an output.
  pinMode(led0, OUTPUT);
  pinMode(led1, OUTPUT);     
  pinMode(led2, OUTPUT);        
}

// the loop routine runs over and over again forever:
void loop() {
  int t=100;

  digitalWrite(led2, HIGH);   // turn the LED off
  delay(t);               // wait
  digitalWrite(led1, HIGH);   // turn the LED off
  delay(t);               // wait
  digitalWrite(led0, LOW);   // turn the LED off
  delay(2*t);               // wait
  
  digitalWrite(led0, HIGH);    // turn the LED on
  delay(t);               // wait
  digitalWrite(led1, LOW);    // turn the LED on
  delay(t);               // wait
  digitalWrite(led2, LOW);    // turn the LED on
  delay(2*t);               // wait
  
  // call "library"
  blinkLED20();

}



