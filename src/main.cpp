#include <WiFi.h>
#include <Wire.h>
#include <SPI.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <Update.h>
#include <time.h>
#include <EEPROM.h>

#include <PubSubClient.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <Adafruit_BMP280.h>
#include <Adafruit_CCS811.h>
#include <Adafruit_NeoPixel.h>

// ---------- OLED ----------
#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_RESET -1
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

// ---------- NeoPixel ----------
#define LED_PIN 17
#define NUM_LEDS 30
Adafruit_NeoPixel strip(NUM_LEDS, LED_PIN, NEO_GRB + NEO_KHZ800);

// ---------- Sensors ----------
#define DHTPIN 25
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

Adafruit_BMP280 bmp;
#define LDR_PIN 34
Adafruit_CCS811 ccs;

// ---------- MULTI WIFI ----------
struct WiFiCred {
  const char* ssid;
  const char* pass;
};

WiFiCred wifiList[] = {
  { "iPhone de: Quim", "quim4444" },   // Hotspot
  { "gencat_ENS_EDU_LAB", "RObOt!c@" }      // WiFi fixa
};

const int WIFI_COUNT = sizeof(wifiList) / sizeof(wifiList[0]);

// ---------- MQTT ----------
const char* mqtt_server = "192.168.1.145";
const int mqtt_port = 1883;
const char* mqtt_user = "esp32user";
const char* mqtt_pass = "esp32pass";

WiFiClient espClient;
PubSubClient client(espClient);

// ---------- OTA ----------
float FW_VERSION = 1.2;
const char* versionURL =
"https://raw.githubusercontent.com/MarcQuimDev/cronos/esp32/version.txt";
const char* firmwareURL =
"https://github.com/MarcQuimDev/cronos/releases/latest/download/firmware.bin";

// ---------- EEPROM ----------
void saveVersion(float v) {
  EEPROM.begin(8);
  EEPROM.put(0, v);
  EEPROM.commit();
}

float readVersion() {
  float v;
  EEPROM.begin(8);
  EEPROM.get(0, v);
  if (isnan(v) || v <= 0) v = 1.0;
  return v;
}

// ---------- WIFI CONNECT ----------
bool connectWiFi() {
  WiFi.mode(WIFI_STA);

  for (int i = 0; i < WIFI_COUNT; i++) {
    Serial.printf("Intentant WiFi: %s\n", wifiList[i].ssid);
    WiFi.begin(wifiList[i].ssid, wifiList[i].pass);

    unsigned long t0 = millis();
    while (WiFi.status() != WL_CONNECTED && millis() - t0 < 15000) {
      delay(500);
      Serial.print(".");
    }

    if (WiFi.status() == WL_CONNECTED) {
      Serial.println("\nWiFi CONNECTAT");
      Serial.println(WiFi.SSID());
      Serial.println(WiFi.localIP());
      return true;
    }

    WiFi.disconnect(true);
    delay(1000);
  }

  Serial.println("❌ Cap WiFi disponible");
  return false;
}

// ---------- MQTT ----------
void reconnect() {
  while (!client.connected()) {
    client.connect("sensor1_esp32", mqtt_user, mqtt_pass);
    delay(2000);
  }
}

// ---------- OTA ----------
void performOTA(float newVersion) {
    Serial.println("Iniciant OTA...");
    display.clearDisplay();
    display.println("Inici OTA...");
    display.display();

    WiFiClientSecure clientSecure;
    clientSecure.setInsecure();
    HTTPClient http;

    http.begin(clientSecure, firmwareURL);
    int httpCode = http.GET();
    if (httpCode != HTTP_CODE_OK) {
        Serial.println("Error HTTP OTA");
        return;
    }

    int total = http.getSize();
    WiFiClient* stream = http.getStreamPtr();

    if (!Update.begin(total)) {
        Serial.println("Update begin error");
        return;
    }

    uint8_t buffer[256];
    int written = 0;

    while (http.connected() && written < total) {
        int len = stream->readBytes(buffer, sizeof(buffer));
        if (len > 0) {
            Update.write(buffer, len);
            written += len;
        }
        delay(1);
    }

    if (Update.end()) {
        Serial.println("OTA OK, reiniciant");
        saveVersion(newVersion);
        ESP.restart();
    } else {
        Serial.println("OTA ERROR");
    }

    http.end();
}

// ---------- CHECK VERSION ----------
bool checkForUpdate(float &newV) {
  WiFiClientSecure clientSecure;
  clientSecure.setInsecure();
  HTTPClient http;

  http.begin(clientSecure, versionURL);
  if (http.GET() != 200) return false;

  newV = http.getString().toFloat();
  return newV > FW_VERSION;
}

// ---------- SETUP ----------
void setup() {
  Serial.begin(115200);

  FW_VERSION = readVersion();
  display.begin(SSD1306_SWITCHCAPVCC, 0x3C);
  display.setTextColor(SSD1306_WHITE);
  display.clearDisplay();
  display.println("Iniciant...");
  display.display();

  if (connectWiFi()) {
    float newV;
    if (checkForUpdate(newV)) performOTA(newV);
  }

  client.setServer(mqtt_server, mqtt_port);
  bmp.begin(0x76);
  dht.begin();
  ccs.begin();
  strip.begin();
  strip.show();
}

// ---------- LOOP ----------
void loop() {
  if (!client.connected()) reconnect();
  client.loop();

  float temp = dht.readTemperature();
  float hum = dht.readHumidity();
  float pres = bmp.readPressure() / 100;
  float bri = analogRead(LDR_PIN);

  static float eCO2 = 400, TVOC = 0;
  if (ccs.available() && !ccs.readData()) {
    eCO2 = ccs.geteCO2();
    TVOC = ccs.getTVOC();
  }

  char payload[200];
  snprintf(payload, sizeof(payload),
    "{\"temp\":%.1f,\"hum\":%.1f,\"pres\":%.1f,\"bri\":%.0f,\"eco2\":%.0f,\"tvoc\":%.0f}",
    temp, hum, pres, bri, eCO2, TVOC);

  client.publish("casa/", payload);
  delay(1000);
}
