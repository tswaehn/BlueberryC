/*
  Blink
  Turns on and off all onboard LEDs 
 
  This example code is in the public domain.

*/



// Pin 13 has an LED connected on most Arduino boards.
// give it a name: (from Left to Right)
int led13 = 13; // default Arduino LED (LED is on if Pin13 is High)
int led21 = 21; // additional CoPiino LED (LED on if Pin21 is Low)
int led20 = 20; // additional CoPiino LED (LED on if Pin20 is Low)

// the setup routine runs once when you press reset:
void setup() {                
  // initialize the digital pin as an output.
  pinMode(led13, OUTPUT);
  pinMode(led21, OUTPUT);     
  pinMode(led20, OUTPUT);        
}

// the loop routine runs over and over again forever:
void loop() {
  int t=100;

  digitalWrite(led20, HIGH);   // turn the LED 20 off
  delay(t);               // wait
  digitalWrite(led21, HIGH);   // turn the LED 21 off
  delay(t);               // wait
  digitalWrite(led13, LOW);   // turn the LED 13 off
  delay(2*t);               // wait
  
  digitalWrite(led13, HIGH);    // turn the LED 13 on
  delay(t);               // wait
  digitalWrite(led21, LOW);    // turn the LED 21 on
  delay(t);               // wait
  digitalWrite(led20, LOW);    // turn the LED 20 on
  delay(2*t);               // wait

}



