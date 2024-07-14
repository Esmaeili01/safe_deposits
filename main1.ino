#include <WiFi.h>
#include "time.h"
#include <MFRC522.h>
#include <LiquidCrystal_I2C.h>
#include <SPI.h>
#include <HTTPClient.h> 

#define RST_PIN  4
#define SS_PIN 5

MFRC522 mfrc522(SS_PIN, RST_PIN);   

const char* ssid     = "A12";
const char* password = "12345679";

const char* ntpServer = "pool.ntp.org";
const long  gmtOffset_sec = 12600;
const int   daylightOffset_sec = 3600;

String URL = "http://192.168.156.215/door/apply.php"; // change the ip address

void setup() {
 // Serial.begin(9600);
  
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
  }

  SPI.begin();
  mfrc522.PCD_Init();
}


// send http 


void loop() {
  String postData = "";
  HTTPClient http; 
  http.begin(URL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postData); 
  String result = "";
  result = http.getString();
  http.end();

  if ( ! mfrc522.PICC_IsNewCardPresent()) 
  {
    return;
  }

  if ( ! mfrc522.PICC_ReadCardSerial()) 
  {
    return;
  }
  
  String UidCard = "";
  for (byte i = 0; i < mfrc522.uid.size; i++)  
  {
    UidCard.concat(String(mfrc522.uid.uidByte[i]  < 0x10 ? " 0" : " "));
    UidCard.concat(String(mfrc522.uid.uidByte[i],  HEX));
  }

  UidCard.toUpperCase();
  postData =  "UIDCard=" + String(UidCard);
  HTTPClient http; 
  http.begin(URL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postData); 
  String result = "";
  result = http.getString();
  http.end();

}
