#include <WiFi.h>
#include <Wire.h>
#include <SPI.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <Update.h>
#include <time.h>
#include <EEPROM.h>

<<<<<<< HEAD
// Llibreries
=======
#include <PubSubClient.h>
>>>>>>> 4a2b20531d041c9ff11c41d04a0c046cce0bb280
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

<<<<<<< HEAD
// --- Wi-Fi ---
const char* ssid = "Fiona2G";
const char* password = "Pampall1g1e$";

// --- ThingSpeak ---
const char* thingspeak_api_key = "TJPZUQ9UXUE0TO15"; // canvia-ho pel teu
const char* thingspeak_server = "http://api.thingspeak.com/update";
=======
// ---------- MULTI WIFI ----------
struct WiFiCred {
  const char* ssid;
  const char* pass;
};
>>>>>>> 4a2b20531d041c9ff11c41d04a0c046cce0bb280

WiFiCred wifiList[] = {
  { "iPhone de: Quim", "quim4444" },   // Hotspot
  { "gencat_ENS_EDU_LAB", "RObOt!c@" }      // WiFi fixa
};

<<<<<<< HEAD
// --- OTA ---
float FW_VERSION = 1.2;
bool otaInProgress = false;
const char* versionURL = "https://raw.githubusercontent.com/MarcQuimDev/cronos/thingspeak/version.txt";
const char* firmwareURL = "https://github.com/MarcQuimDev/cronos/releases/latest/download/firmware.bin";

// --- EEPROM ---
void saveVersion(float version) {
    EEPROM.begin(4);
    EEPROM.put(0, version);
    EEPROM.commit();
=======
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
>>>>>>> 4a2b20531d041c9ff11c41d04a0c046cce0bb280
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

<<<<<<< HEAD
// --- Temps ---
void temps() {
    struct tm timeinfo;
    static int lastMinute = -1;
    if (getLocalTime(&timeinfo)) {
        if (timeinfo.tm_min != lastMinute) lastMinute = timeinfo.tm_min;
        display.setCursor(0,0);
        display.setTextSize(2);
        display.printf("   %02d:%02d\n", timeinfo.tm_hour, timeinfo.tm_min);
    }
}

// --- Enviar dades a ThingSpeak ---
void sendToThingSpeak(float temp, float hum, float pres, float bri, float eCO2, float TVOC) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        String url = String(thingspeak_server) + "?api_key=" + thingspeak_api_key +
                     "&field1=" + String(temp) +
                     "&field2=" + String(hum) +
                     "&field3=" + String(pres/100.0) +
                     "&field4=" + String((bri/500.0)*100) +
                     "&field5=" + String(eCO2) +
                     "&field6=" + String(TVOC);
        http.begin(url);
        int httpCode = http.GET();
        if (httpCode > 0) {
            Serial.printf("ThingSpeak resposta: %d\n", httpCode);
        } else {
            Serial.println("Error enviant a ThingSpeak");
        }
        http.end();
    }
}

// --- OTA ---
void showOTAProgress(int percent) {
    display.clearDisplay();
    display.setTextSize(1);
    display.setCursor(0,0);
    display.println("Actualitzant OTA...");
    display.drawRect(0, 20, 128, 10, SSD1306_WHITE);
    display.fillRect(0, 20, map(percent,0,100,0,128), 10, SSD1306_WHITE);
    display.setCursor(0,40);
    display.printf("%d %%", percent);
    display.display();
=======
// ---------- MQTT ----------
void reconnect() {
  while (!client.connected()) {
    client.connect("sensor1_esp32", mqtt_user, mqtt_pass);
    delay(2000);
  }
>>>>>>> 4a2b20531d041c9ff11c41d04a0c046cce0bb280
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

<<<<<<< HEAD


// --- Setup ---
=======
// ---------- SETUP ----------
>>>>>>> 4a2b20531d041c9ff11c41d04a0c046cce0bb280
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

<<<<<<< HEAD
    setup_wifi();

    display.clearDisplay();
    display.setTextSize(1);
    display.println("WIFI OK");
    display.display();

    // Comprovar i fer OTA si cal
    float newVersion = 0;
    if (checkForUpdate(newVersion)) {
        Serial.println("Nova versió disponible. Inici OTA...");
        performOTA(newVersion);
    } else {
        Serial.println("Tens la última versió.");
    }

    // Temps
    configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);

    // Sensors
    if (!bmp.begin(0x76)) Serial.println("BMP280 ERROR!");
    bmp.setSampling(Adafruit_BMP280::MODE_NORMAL,
                    Adafruit_BMP280::SAMPLING_X2,
                    Adafruit_BMP280::SAMPLING_X16,
                    Adafruit_BMP280::FILTER_X16,
                    Adafruit_BMP280::STANDBY_MS_500);
    dht.begin();
    if (!ccs.begin()) Serial.println("CCS811 ERROR!");
    else ccs.setDriveMode(CCS811_DRIVE_MODE_1SEC);

    strip.begin();
    strip.show();
=======
  client.setServer(mqtt_server, mqtt_port);
  bmp.begin(0x76);
  dht.begin();
  ccs.begin();
  strip.begin();
  strip.show();
>>>>>>> 4a2b20531d041c9ff11c41d04a0c046cce0bb280
}

// ---------- LOOP ----------
void loop() {
<<<<<<< HEAD
    static unsigned long lastSend = 0;
    unsigned long now = millis();

    static unsigned long lastOTA = 0;
    unsigned long nowOTA = millis();

    float temp = dht.readTemperature();
    float hum = dht.readHumidity();
    float pres = (bmp.readPressure()/100);
    float bri = analogRead(LDR_PIN);
    static float eCO2 = 400, TVOC = 0;
=======
  if (!client.connected()) reconnect();
  client.loop();

  float temp = dht.readTemperature();
  float hum = dht.readHumidity();
  float pres = bmp.readPressure() / 100;
  float bri = analogRead(LDR_PIN);
>>>>>>> 4a2b20531d041c9ff11c41d04a0c046cce0bb280

  static float eCO2 = 400, TVOC = 0;
  if (ccs.available() && !ccs.readData()) {
    eCO2 = ccs.geteCO2();
    TVOC = ccs.getTVOC();
  }

  char payload[200];
  snprintf(payload, sizeof(payload),
    "{\"temp\":%.1f,\"hum\":%.1f,\"pres\":%.1f,\"bri\":%.0f,\"eco2\":%.0f,\"tvoc\":%.0f}",
    temp, hum, pres, bri, eCO2, TVOC);

<<<<<<< HEAD
    static unsigned long lastOLED=0;
    unsigned long nowOLED = millis();
    static bool pantalla1=true;
    if (nowOLED - lastOLED >= 2000) {pantalla1=!pantalla1; lastOLED=nowOLED;}

    display.clearDisplay();
    temps();
    display.setTextSize(1);
    display.setCursor(0,20);
    display.setCursor(0, 20);
    display.setTextSize(1);

    if (pantalla1) {
        display.println("Temp: " + String(temp,1) + "C\n");
        display.println("Hum: " + String(hum,1) + "%\n");
        display.println("Pres: " + String(pres/100.0,1) + " hPa\n");
    } else {
        display.println("Bri: " + String((bri/500.0)*100,1) + "%\n");
        display.println("eCO2: " + String(eCO2,0) + " ppm\n");
        display.println("TVOC: " + String(TVOC,0) + " ppb\n");
    }

    display.display();


    // Enviar dades cada 60 segons a ThingSpeak
    if (now - lastSend >= 60000) {
        lastSend = now;
        sendToThingSpeak(temp, hum, pres, bri, eCO2, TVOC);
    }
    // Comprovar cada 60 segons si hi han actualitzacions
    if (now - lastSend >= 60000) {
        float newVersion = 0;
        if (checkForUpdate(newVersion)) {
            Serial.println("Nova versió disponible. Inici OTA...");
            performOTA(newVersion);
        } else {
            Serial.println("Tens la última versió.");
        }
    }

    delay(100);
=======
  client.publish("casa/", payload);
  delay(1000);
>>>>>>> 4a2b20531d041c9ff11c41d04a0c046cce0bb280
}
