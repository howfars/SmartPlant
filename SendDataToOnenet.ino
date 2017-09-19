/*
Name:    SendDataToOnenet
Created:  2017/7/9 10:14:17
Author: GEHAO YU
*/
// the setup function runs once when you press reset or power the board
#include <ESP8266.h>
#include<Wire.h>
#include<AM2321.h>
#if defined(__AVR_ATmega32U4__) || defined(__AVR_ATmega1284P__) || defined (__AVR_ATmega644P__) || defined(__AVR_ATmega128RFA1__)
#define EspSerial Serial1
#define UARTSPEED  115200
#endif

#define SSID        "6"
#define PASSWORD    "qlchy123"
#define HOST_NAME   "api.heclouds.com"
#define DEVICEID    "9275610"
#define PROJECTID   "90870"
#define HOST_PORT   (80)
String apiKey = "eqhPnXKYnzGx4iMvVpHQ=5uW1CI=";
static const uint8_t stateLEDPin = 4;
char buf[10];
int start = 0;
int end = 0;
int state=0;
int delay_pin=1;
int water_pin=10;

String mCottenData;
String jsonToSend;
String postString;

#define INTERVAL_sensor 2000
unsigned long sensorlastTime = millis();

float tempOLED, humiOLED, lightnessOLED, moisOLED;

ESP8266 wifi(&EspSerial);
void setup(void)
{
  pinMode(A2,INPUT);
  Serial.begin(9600);
  EspSerial.begin(115200);
  while (!Serial);
  Serial.print("setup begin\r\n");
  delay(100);
  WifiInit(EspSerial, UARTSPEED);
  Serial.print("FW Version:");
  Serial.println(wifi.getVersion().c_str());

  if (wifi.setOprToStation()) {
    Serial.print("to station ok\r\n");
  }
  else {
    Serial.print("to station err\r\n");
  }

  if (wifi.joinAP(SSID, PASSWORD)) {
    //wifi.setWiFiconnected(true);
    Serial.print("Join AP success\r\n");
    Serial.print("IP:");
    Serial.println(wifi.getLocalIP().c_str());
  }
  else {
    //wifi.setWiFiconnected(false);
    Serial.print("Join AP failure\r\n");
    Serial.print("Make sure your SSID, PASS correctly!\r\n");
  }


  if (wifi.disableMUX()) {
    Serial.print("single ok\r\n");
  }
  else {
    Serial.print("single err\r\n");
  }
  Serial.print("setup end\r\n");
}

void loop(void)
{ 
  updateData();
  AM2321 am2321;
  am2321.read();
  tempOLED=am2321.temperature/10.0;
  humiOLED=am2321.humidity/10.0;
  lightnessOLED = analogRead(A2);
  moisOLED = map(analogRead(A0), 0, 2047, 0, 100);
  if(moisOLED<10){
    digitalWrite(water_pin,HIGH);
    //delay(500);
    //digitalWrite(water_pin,LOW);
  }else{
    digitalWrite(water_pin,LOW);
  }
  delay(1000);
}





void updateData() {  //上传数据函数

  uint8_t buffer[1024] = {0};
  char infoData[60] = { 0 };

  if (wifi.createTCP(HOST_NAME, HOST_PORT)) {   //创建tcp连接
    Serial.print("create tcp ok\r\n");
  }
  else {
    Serial.print("create tcp err\r\n");
  }



  jsonToSend = "{\"Temperature\":";       //根据数据类型修改格式
  dtostrf(tempOLED, 1, 2, buf);
  jsonToSend += "\"" + String(buf) + "\"";
  jsonToSend += ",\"Humidity\":";
  dtostrf(humiOLED, 1, 2, buf);
  jsonToSend += "\"" + String(buf) + "\"";
  jsonToSend += ",\"Light\":";
  dtostrf(lightnessOLED, 1, 2, buf);
  jsonToSend += "\"" + String(buf) + "\"";
  jsonToSend += ",\"Moisture\":";
  dtostrf(moisOLED, 1, 2, buf);
  jsonToSend += "\"" + String(buf) + "\"";
  jsonToSend += "}";



  postString = "POST /devices/";      //根据数据类型修改格式
  postString += DEVICEID;
  postString += "/datapoints?type=3 HTTP/1.1";
  postString += "\r\n";
  postString += "api-key:";
  postString += apiKey;
  postString += "\r\n";
  postString += "Host:api.heclouds.com\r\n";
  postString += "Connection:close\r\n";
  postString += "Content-Length:";
  postString += jsonToSend.length();
  postString += "\r\n";
  postString += "\r\n";
  postString += jsonToSend;
  postString += "\r\n";
  postString += "\r\n";
  postString += "\r\n";

  const char *postArray = postString.c_str();    //用指针指向数据

  Serial.println(postArray);                      //串口打印数据

  wifi.send((const uint8_t*)postArray, strlen(postArray)); //发送数据
  if (wifi.releaseTCP()) {
    Serial.print("release tcp ok\r\n");
  }
  else {
    Serial.print("release tcp err\r\n");
  }

  postArray = NULL;

}


void readByAM2321()
{
  AM2321 am2321;
  am2321.read();
}

