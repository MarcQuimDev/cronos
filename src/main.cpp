#include <WiFi.h>
#include <Wire.h>
#include <SPI.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <Update.h>
#include <time.h>
#include <EEPROM.h>

// Llibreries
#include <PubSubClient.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <Adafruit_BMP280.h>
#include <Adafruit_CCS811.h>
#include <Adafruit_NeoPixel.h>

// --- OLED --- 
#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_RESET -1
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

// --- NeoPixel ---
#define LED_PIN 17
#define NUM_LEDS 30
Adafruit_NeoPixel strip(NUM_LEDS, LED_PIN, NEO_GRB + NEO_KHZ800);

// --- DHT Sensor ---
#define DHTPIN 25
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// --- BMP280 Sensor ---
Adafruit_BMP280 bmp;

// --- TEMT6000 Sensor ---
#define LDR_PIN 34

// --- CCS811 Sensor ---
Adafruit_CCS811 ccs;

// --- Wi-Fi ---
const char* ssid = "iPhone de: Quim";
const char* password = "quim4444";

// --- Temps ---
const char* ntpServer = "pool.ntp.org";
const long gmtOffset_sec = 3600;
const int daylightOffset_sec = 3600;

// --- MQTT ---
const char* mqtt_server = "192.168.1.145";
const int mqtt_port = 1883;
const char* mqtt_user = "esp32user";
const char* mqtt_pass = "esp32pass";

// --- Clients ---
WiFiClient espClient;
PubSubClient client(espClient);

// --- OTA ---
float FW_VERSION = 1.0;
bool otaInProgress = false;

const char* versionURL = "https://raw.githubusercontent.com/MarcQuimDev/cronos/esp32/version.txt";
const char* firmwareURL = "https://github.com/MarcQuimDev/cronos/releases/latest/download/firmware.bin";

// --- EEPROM ---
void saveVersion(float version) {
    EEPROM.begin(4);
    EEPROM.put(0, version);
    EEPROM.commit();
}

float readVersion() {
    float version;
    EEPROM.begin(4);
    EEPROM.get(0, version);
    if (isnan(version) || version <= 0) version = 1.0; // fallback
    return version;
}

// --- Wi-Fi ---
void setup_wifi() {
    Serial.print("Connectant a "); Serial.println(ssid);
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("\nWiFi connectat!");
    Serial.print("IP: "); Serial.println(WiFi.localIP());
}

// --- MQTT ---
void reconnect() {
    while (!client.connected()) {
        Serial.print("Connectant al servidor MQTT...");
        if (client.connect("sensor1_esp32", mqtt_user, mqtt_pass)) Serial.println("connectat!");
        else {
            Serial.print("Error, rc="); Serial.print(client.state());
            Serial.println(" — reintentant en 5 segons");
            delay(5000);
        }
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
}

void performOTA(float newVersion) {
    otaInProgress = true;
    display.clearDisplay();
    display.setCursor(0,0);
    display.println("Inici OTA...");
    display.display();

    WiFiClientSecure clientSecure;
    clientSecure.setInsecure();

    HTTPClient http;
    http.setFollowRedirects(HTTPC_STRICT_FOLLOW_REDIRECTS);
    http.begin(clientSecure, firmwareURL);

    int httpCode = http.GET();
    Serial.printf("HTTP code: %d\n", httpCode);
    if (httpCode != HTTP_CODE_OK) {
        display.println("Error HTTP");
        display.display();
        delay(3000);
        otaInProgress = false;
        http.end();
        return;
    }

    int total = http.getSize();
    WiFiClient *stream = http.getStreamPtr();
    if (!Update.begin(total)) {
        display.println("Update FAIL");
        display.display();
        otaInProgress = false;
        http.end();
        return;
    }

    int written = 0;
    uint8_t buffer[256];

    while (http.connected() && written < total) {
        size_t available = stream->available();
        if (available) {
            int r = stream->readBytes(buffer, min((int)available, 256));
            Update.write(buffer, r);
            written += r;
            int percent = (written*100)/total;
            showOTAProgress(percent);
        }
        delay(1);
    }

    if (Update.end()) {
        display.clearDisplay();
        display.println("OTA OK!");
        display.println("Reiniciant...");
        display.display();
        saveVersion(newVersion);
        FW_VERSION = newVersion;
        delay(2000);
        ESP.restart();
    } else {
        display.println("OTA ERROR");
        display.display();
    }

    http.end();
}

// --- Comprovar versió nova ---
bool checkForUpdate(float &newVersion) {
    WiFiClientSecure clientSecure;
    clientSecure.setInsecure();
    HTTPClient http;
    http.begin(clientSecure, versionURL);

    int httpCode = http.GET();
    if (httpCode != 200) {
        http.end();
        return false;
    }

    newVersion = http.getString().toFloat();
    Serial.print("Versió disponible: "); Serial.println(newVersion);
    http.end();
    return newVersion > FW_VERSION;
}

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

// --- Setup ---
void setup() {
    Serial.begin(115200);
    Serial.println("Iniciant ESP32...");

    FW_VERSION = readVersion();
    Serial.print("Versió llegida EEPROM: "); Serial.println(FW_VERSION);

    display.begin(SSD1306_SWITCHCAPVCC, 0x3C);
    display.setTextColor(SSD1306_WHITE);
    display.setCursor(0,0);
    display.println("Iniciant ESP32...");
    display.display();

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

    // MQTT
    client.setServer(mqtt_server, mqtt_port);
    if (client.connect("sensor1_esp32", mqtt_user, mqtt_pass)) display.println("MQTT OK");
    else display.println("MQTT ERROR");
    display.display();

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
}

// --- Loop ---
void loop() {
    if (!client.connected()) reconnect();
    client.loop();

    float temp = dht.readTemperature();
    float hum = dht.readHumidity();
    float pres = bmp.readPressure();
    float bri = analogRead(LDR_PIN);
    static float eCO2 = 400, TVOC = 0;

    if (ccs.available() && !ccs.readData()) {
        eCO2 = ccs.geteCO2();
        TVOC = ccs.getTVOC();
    }

    for (int i=0;i<NUM_LEDS;i++) strip.setPixelColor(i, bri, bri, bri);
    strip.show();

    static unsigned long lastOLED=0;
    unsigned long nowOLED = millis();
    static bool pantalla1=true;
    if (nowOLED - lastOLED >= 2000) {pantalla1=!pantalla1; lastOLED=nowOLED;}

    display.clearDisplay();
    temps();
    display.setTextSize(1);
    display.setCursor(0,20);
    if (pantalla1) display.printf("Temp: %.1fC\nHum: %.1f%%\nPres: %.1f hPa", temp, hum, pres/100);
    else display.printf("Bri: %.1f%%\neCO2: %.0f ppm\nTVOC: %.0f ppb", (bri/500.0)*100, eCO2, TVOC);
    display.display();

    static unsigned long lastSerial=0;
    unsigned long nowSerial = millis();
    if (nowSerial - lastSerial > 1000) {
        lastSerial=nowSerial;
        char payload[200];
        snprintf(payload,sizeof(payload),
            "{\"temp\": %.2f, \"hum\": %.0f, \"pres\": %.1f, \"bri\": %.1f, \"eco2\": %.0f, \"tvoc\": %.0f}",
            temp, hum, pres/100, (bri/500.0)*100, eCO2, TVOC);
        Serial.println(payload);
        client.publish("casa/", payload);
    }
}
