#include <WiFi.h>
#include <Wire.h>
#include <SPI.h>
#include <HTTPClient.h>
#include <WiFiClientSecure.h>
#include <Update.h>
#include <time.h>

//llibreries
#include <PubSubClient.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <Adafruit_BMP280.h>
#include <Adafruit_CCS811.h>
#include <Adafruit_NeoPixel.h>


// --- OLED --- 
#define SCREEN_WIDTH 128  // OLED display width, in pixels
#define SCREEN_HEIGHT 64  // OLED display height, in pixels
#define OLED_RESET     -1 // Reset pin (or -1 if sharing Arduino reset pin)
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

// --- NeoPixel ---
#define LED_PIN     17
#define NUM_LEDS    30
TaskHandle_t TaskLEDs;
uint8_t brightness = 10;
Adafruit_NeoPixel strip(NUM_LEDS, LED_PIN, NEO_GRB + NEO_KHZ800);

// --- DHT Sensor ---
#define DHTPIN 25
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// --- BMP280 Sensor ---
Adafruit_BMP280 bmp; // use I2C interface

// --- TEMT6000 Sensor ---
#define LDR_PIN 34

// --- CCS811 Sensor ---
Adafruit_CCS811 ccs;

// --- Wi-Fi Config ---
const char* ssid = "iPhone de: Quim";
const char* password = "quim4444";

// --- TEMPS Config
const char* ntpServer = "pool.ntp.org";
// Zona horària Barcelona (Europe/Madrid)
const long gmtOffset_sec = 3600;      // UTC+1
const int daylightOffset_sec = 3600;  // +1 hora en estiu

// --- MQTT Config ---
const char* mqtt_server = "192.168.1.145";
const int mqtt_port = 1883;
const char* mqtt_user = "esp32user";
const char* mqtt_pass = "esp32pass";

// --- Clients ---
WiFiClient espClient;
PubSubClient client(espClient);

// --- Connecta al Wi-Fi ---
void setup_wifi() {
    delay(10);
    Serial.println();
    Serial.print("Connectant a ");
    Serial.println(ssid);

    WiFi.begin(ssid, password);

    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }

    Serial.println("");
    Serial.println("WiFi connectat!");
    Serial.print("IP: ");
    Serial.println(WiFi.localIP());
}

// --- Reconnecta a MQTT si cal ---
void reconnect() {
    while (!client.connected()) {
        Serial.print("Connectant al servidor MQTT...");
        if (client.connect("sensor1_esp32", mqtt_user, mqtt_pass)) {
            Serial.println("connectat!");
        } else {
            Serial.print("Error, rc=");
            Serial.print(client.state());
            Serial.println(" — reintentant en 5 segons");
            delay(5000);
        }
    }
}

//OTA
#define FW_VERSION 1.1
bool otaInProgress = false;

const char* versionURL = "https://raw.githubusercontent.com/MarcQuimDev/cronos/esp32/version.txt";
const char* firmwareURL = "https://github.com/MarcQuimDev/cronos/releases/latest/download/firmware.bin";

void showOTAProgress(int percent) {
    display.clearDisplay();
    display.setTextSize(1);
    display.setCursor(0, 0);
    display.println("Actualitzant OTA...");
    
    display.drawRect(0, 20, 128, 10, SSD1306_WHITE);
    display.fillRect(0, 20, map(percent, 0, 100, 0, 128), 10, SSD1306_WHITE);

    display.setCursor(0, 40);
    display.printf("%d %%", percent);
    display.display();
}
void performOTA() {
    otaInProgress = true;

    display.clearDisplay();
    display.setCursor(0,0);
    display.println("Inici OTA...");
    display.display();

    WiFiClientSecure clientSecure;
    clientSecure.setInsecure();

    HTTPClient http;
    http.setFollowRedirects(HTTPC_STRICT_FOLLOW_REDIRECTS); // ← CLAU
    http.begin(clientSecure, firmwareURL);

    int httpCode = http.GET();
    Serial.printf("HTTP code: %d\n", httpCode);

    if (httpCode != HTTP_CODE_OK) {
        display.println("Error HTTP");
        display.display();
        delay(3000);
        otaInProgress = false;
        return;
    }

    int total = http.getSize();
    WiFiClient *stream = http.getStreamPtr();

    if (!Update.begin(total)) {
        display.println("Update FAIL");
        display.display();
        otaInProgress = false;
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

            int percent = (written * 100) / total;

            display.clearDisplay();
            display.println("Actualitzant OTA");
            display.drawRect(0, 20, 128, 10, SSD1306_WHITE);
            display.fillRect(0, 20, map(percent, 0, 100, 0, 128), 10, SSD1306_WHITE);
            display.setCursor(0, 40);
            display.printf("%d %%", percent);
            display.display();
        }
        delay(1);
    }

    if (Update.end()) {
        display.clearDisplay();
        display.println("OTA OK!");
        display.println("Reiniciant...");
        display.display();
        delay(2000);
        ESP.restart();
    } else {
        display.println("OTA ERROR");
        display.display();
    }

    http.end();
}


bool checkForUpdate() {
    WiFiClientSecure clientSecure;
    clientSecure.setInsecure(); // ← CLAU
    HTTPClient http;
    http.begin(clientSecure, versionURL);

    int httpCode = http.GET();
    if (httpCode != 200) {
        http.end();
        return false;
    }

    float newVersion = http.getString().toFloat();
    Serial.println(newVersion);
    http.end();

    return newVersion > FW_VERSION;
}




void temps(){
    struct tm timeinfo;
    static int lastMinute = -1;
    // --- Temps ---
    if (getLocalTime(&timeinfo)) {
        if (timeinfo.tm_min != lastMinute) {
            lastMinute = timeinfo.tm_min;
        }
        display.setCursor(0,0);
        display.setTextSize(2);
        display.printf("   %02d:%02d\n", timeinfo.tm_hour, timeinfo.tm_min);
    }
}

// --- Setup ---
void setup() {
    Serial.begin(115200);
    Serial.println("Iniciant ESP32...");
    //oled
    display.begin(SSD1306_SWITCHCAPVCC, 0x3C);
    display.setTextColor(SSD1306_WHITE);
    display.setCursor(0, 0);
    display.println("Iniciant ESP32...");
    display.display();

    //wifi + mqtt
    setup_wifi();
    display.clearDisplay();
    display.setTextSize(1);
    if (WiFi.status() == WL_CONNECTED) {
        display.println("WIFI         OK");
    }
    display.display();
    if (checkForUpdate()) {
        performOTA();
    }else{
        Serial.println("No hi ha servei d'OTA disponible...");
    }

    delay(200);
    client.setServer(mqtt_server, mqtt_port);
    if (client.connect("sensor1_esp32", mqtt_user, mqtt_pass)) {
        display.println("MQTT         OK");
        Serial.println("MQTT configurat!");
    } else {
        display.println("MQTT ERROR");
    }
    display.display();
    delay(200);
    
    //ota
    if (checkForUpdate()) {
        performOTA();
    }else{
        Serial.println("No hi ha servei d'OTA disponible...");
    }

    //temps
      //|-> configuracio
    configTime(gmtOffset_sec, daylightOffset_sec, ntpServer);
    Serial.println("NTP configurat!");
    struct tm timeinfo;
    getLocalTime(&timeinfo);
      //|-> variables
    int hora = timeinfo.tm_hour;
    int minut = timeinfo.tm_min;

    char horaString[20];
    strftime(horaString, sizeof(horaString), "%H:%M", &timeinfo);
    display.print("HORA ");
    display.println(horaString);
    display.display();

    //bmp280
    if (!bmp.begin(0x76)) {
    Serial.println("No s'ha pogut inicialitzar el BMP280!");
    }
    /* Default settings from datasheet. */
    bmp.setSampling(Adafruit_BMP280::MODE_NORMAL,     /* Operating Mode. */
                  Adafruit_BMP280::SAMPLING_X2,     /* Temp. oversampling */
                  Adafruit_BMP280::SAMPLING_X16,    /* Pressure oversampling */
                  Adafruit_BMP280::FILTER_X16,      /* Filtering. */
                  Adafruit_BMP280::STANDBY_MS_500); /* Standby time. */
    display.println("\nBMP280       OK");
    display.display();

    //dht11
    dht.begin();
    display.println("DHT11        OK");
    display.display();
    //ccs811
    if(!ccs.begin()){
        Serial.println("CCS811 ERROR");
        display.println("CCS811 ERROR");
    } else{
        Serial.println("CCS811 OK");
        display.println("CCS811       OK");
    }
    ccs.setDriveMode(CCS811_DRIVE_MODE_1SEC);
    display.display();
    delay(1000);
}

// --- Loop ---
void loop() {
    //connectar mqtt
    //if (!client.connected()) {
    //    reconnect();
    //}
    client.loop();  // manté viva la connexió


    // --- Dades ---
    float temp = dht.readTemperature();  
    float hum = dht.readHumidity();   
    float pres = bmp.readPressure();
    float bri = analogRead(LDR_PIN);
    static float eCO2 = 400;  // valor per defecte
    static float TVOC = 0;
    
    if (ccs.available()) {
        if (!ccs.readData()) {
            eCO2 = ccs.geteCO2();
            TVOC = ccs.getTVOC();
        } else {
            Serial.println("Error llegint CCS811");
        }
    }
    
    // leds 
    int r = bri;
    int g = bri;
    int b = bri;
    // Assigna el color a tots els LEDs
    for (int i = 0; i < NUM_LEDS; i++) {
        strip.setPixelColor(i, r,g,b);
    }
    strip.show();
    
    
    static unsigned long lastMsgOLED = 0;
    unsigned long nowOLED = millis();
    static bool pantalla1 = true;
    
    if (nowOLED - lastMsgOLED >= 2000) {
    pantalla1 = !pantalla1; // alterna pantalla
    lastMsgOLED = nowOLED;
    }
    
    static unsigned long lastMsgSerial = 0;
    unsigned long nowSerial = millis();
    
    if (pantalla1) {
        display.clearDisplay();
        temps();
        // --- Pantalla 1 ---
        display.setTextSize(1);
        display.setCursor(0, 20);
        display.print("Temperatura: ");
        display.print(temp);
        display.println(" C");
        display.print("\nHumitat: ");
        display.print(hum);
        display.println(" %");
        display.print("\nPressio: ");
        display.print(pres/100);
        display.println(" HPa");
        display.display();
    }
    else if (!pantalla1)
    {
        // --- Pantalla 2 ---
        display.clearDisplay();
        temps();
        display.setTextSize(1);
        display.setCursor(0, 20);
        display.print("Brillantor: ");
        display.print((bri/500)*100);
        display.println(" %");
        display.print("\neCO2: ");
        display.print(eCO2);
        display.println(" ppm");
        display.print("\nTVOC: ");
        display.print(TVOC);
        display.println(" ppb");
        display.display();
    }
    
    if (nowSerial - lastMsgSerial > 1000) {
        lastMsgSerial = nowSerial;
        
        // --- Actualitza Serial ---
        Serial.print("Temperatura: ");
        Serial.println(temp);
        Serial.print("Humitat: ");
        Serial.println(hum);
        Serial.print("Pressio: ");
        Serial.println(pres);
        Serial.print("Brillantor: ");
        Serial.print((bri/500)*100);
        Serial.println(" %");
        Serial.print("eCO2: ");
        Serial.print(eCO2);
        Serial.println(" ppm");
        Serial.print("TVOC: ");
        Serial.print(TVOC);
        Serial.println(" ppb");
        
        // --- Envia JSON per MQTT ---
        char payload[200];
        snprintf(payload, sizeof(payload), "{\"temperatura\": %.2f, \"humitat\": %.0f, \"pressio\": %.1f, \"brillantor\": %.1f, \"eco2\": %.0f, \"tvoc\": %.0f}", temp, hum, pres/100, (bri/500)*100, eCO2, TVOC);
        
        Serial.print("Enviant JSON: ");
        Serial.println(payload);
        
        if (client.publish("casa/", payload)) {
            Serial.println("JSON publicat correctament!");
        } else {
            Serial.println("Error publicant JSON!");
        }
    
        return;
}
}
