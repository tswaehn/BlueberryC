
/*
  Blink
  Turns on an LED on for one second, then off for one second, repeatedly.
 
  This example code is in the public domain.
 */
 
// define LED20
int led = 20;


// 
void blink(){

  static char i=0;

  // invert i
  i= 1-i;
  
  // switch LED depending on i
  if (i){
	  digitalWrite(led, HIGH);   // turn the LED on (HIGH is the voltage level)
  } else {
    digitalWrite(led, LOW);    // turn the LED off by making the voltage LOW
  }
  
  
}


// the setup routine runs once when you press reset:
void setup() {                
  // initialize the digital pin as an output.
  pinMode(led, OUTPUT); 
  
  // opens serial port, sets data rate to 115200 bps
  Serial1.begin(115200);
  Serial1.println("- start program - ");  
  
}

// the loop routine runs over and over again forever:
void loop() {
  
  static unsigned long startTime=0;
  unsigned long currentTime;
  static char x=0;

  // print every 100ms something
  currentTime= millis();
  if ((currentTime - startTime) > 100){
    
    // increment some value
    x++;
    
    // define a sensor value line
    Serial1.print( "::" );
    // add sensor value
    Serial1.print( x, DEC );
    // add spacer
    Serial1.print(" ");
    // add another sensor value
		Serial1.print(128-x, DEC);    

    // finalize sensor line
    Serial1.println();
    
  }
  
}



