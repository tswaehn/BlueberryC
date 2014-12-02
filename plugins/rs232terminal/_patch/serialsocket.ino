int incomingByte = 0;   // for incoming serial data
 
void setup() {
  
  // opens serial port, sets data rate to 115200 bps
  Serial1.begin(115200);     
  pinMode(20, OUTPUT); 
  pinMode(21, OUTPUT); 
}

char led20value=0;
char led21value=0;
int time=0;
 
void loop() {

  // send data only when you receive data:
  if (Serial1.available() > 0) {

    // read the incoming byte:
    incomingByte = Serial1.read();
    
    incomingByte = toupper( incomingByte );            

    // say what you got:
    Serial1.write(incomingByte);
    
    digitalWrite( 20, led20value);
    led20value=1-led20value;
  }

  time++;
  if (time>30000){
    time=0;
    digitalWrite( 21, led21value );
    led21value=1-led21value;
  }

} 
