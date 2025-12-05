#include <WiFi.h>
#include <Wire.h>
#include <SPI.h>

//llibreries
#include <PubSubClient.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>
#include <Adafruit_BMP280.h>
#include <Adafruit_CCS811.h>

// --- OLED --- 
#define SCREEN_WIDTH 128  // OLED display width, in pixels
#define SCREEN_HEIGHT 64  // OLED display height, in pixels
#define OLED_RESET     -1 // Reset pin (or -1 if sharing Arduino reset pin)
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

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
const char* ssid = "Fiona2G";
const char* password = "Pampall1g1e$";

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

// --- Setup ---
void setup() {
    Serial.begin(115200);
    Serial.println("Iniciant ESP32...");
    //oled
    display.begin(SSD1306_SWITCHCAPVCC, 0x3C);
    display.setTextColor(SSD1306_WHITE);
    display.setCursor(0, 0);

    //wifi + mqtt
    setup_wifi();
    display.clearDisplay();
    display.setTextSize(2);
    if (WiFi.status() == WL_CONNECTED) {
        display.println("WIFI OK");
    }
    display.display();
    delay(200);
    client.setServer(mqtt_server, mqtt_port);
    if (client.connect("sensor1_esp32", mqtt_user, mqtt_pass)) {
        display.println("MQTT OK");
        Serial.println("MQTT configurat!");
    } else {
        display.println("MQTT ERROR");
    }

    display.display();
    delay(200);

    //bmp280
    if (!bmp.begin(0x76)) {  // dirección I2C del BMP280, a veces es 0x76 o 0x77
    Serial.println("No s'ha pogut inicialitzar el BMP280!");
    while (1) delay(10); // se queda aquí para avisarte
    }
    /* Default settings from datasheet. */
    bmp.setSampling(Adafruit_BMP280::MODE_NORMAL,     /* Operating Mode. */
                  Adafruit_BMP280::SAMPLING_X2,     /* Temp. oversampling */
                  Adafruit_BMP280::SAMPLING_X16,    /* Pressure oversampling */
                  Adafruit_BMP280::FILTER_X16,      /* Filtering. */
                  Adafruit_BMP280::STANDBY_MS_500); /* Standby time. */

    //dht11
    dht.begin();
    
    //ccs811
    if(!ccs.begin()){
        Serial.println("CCS811 ERROR");
    } else{
        Serial.println("CCS811 Iniciat");
    }
    ccs.setDriveMode(CCS811_DRIVE_MODE_1SEC);
    display.println("SENSORS OK");
    display.display();
    delay(500);
}

// --- Loop ---
void loop() {
    if (!client.connected()) {
        reconnect();
    }
    client.loop();  // Manté viva la connexió
    
    // --- Dades ---
    float temp = dht.readTemperature();  
    float hum = dht.readHumidity();   
    float pres = bmp.readPressure();
    float bri = analogRead(LDR_PIN);
    float eCO2 = 0;
    float TVOC = 0;

    ccs.setEnvironmentalData(hum, temp);

    if (ccs.available()) {
        if (!ccs.readData()) {
            eCO2 = ccs.geteCO2();
            TVOC = ccs.getTVOC();
        } else {
            Serial.println("Error llegint el CCS811");
        }
    }


    static unsigned long lastMsgOLED = 0;
    unsigned long nowOLED = millis();

    static unsigned long lastMsgSerial = 0;
    unsigned long nowSerial = millis();

    if (nowOLED - lastMsgOLED > 500) {
        lastMsgOLED = nowOLED;
        
        display.setTextSize(1);

        // --- Pantalla 1 ---
        display.clearDisplay();
        display.setCursor(0, 0);
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
        delay(2000);
        // --- Pantalla 2 ---
        display.clearDisplay();
        display.setCursor(0, 0);
        display.print("\nBrillantor: ");
        display.print((bri/500)*100);
        display.println(" %");
        display.print("\neCO2: ");
        display.print(eCO2);
        display.println(" ppm");
        display.print("\nTVOC: ");
        display.print(TVOC);
        display.println(" ppb");
        display.display();
        delay(2000);

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
        char payload[100];
        snprintf(payload, sizeof(payload), "{\"temperatura\": %.2f, \"humitat\": %.0f, \"pressio\": %.1f}", temp, hum, pres/100);

        Serial.print("Enviant JSON: ");
        Serial.println(payload);

        if (client.publish("casa/", payload)) {
            Serial.println("JSON publicat correctament!");
        } else {
            Serial.println("Error publicant JSON!");
        }
    }
}
