#include <WiFi.h>
#include <PubSubClient.h>
#include <Adafruit_Sensor.h>
#include <DHT.h>
#include <Wire.h>
#include <Adafruit_GFX.h>
#include <Adafruit_SSD1306.h>

// --- OLED --- 
#define SCREEN_WIDTH 128  // OLED display width, in pixels
#define SCREEN_HEIGHT 64  // OLED display height, in pixels
#define OLED_RESET     -1 // Reset pin (or -1 if sharing Arduino reset pin)
Adafruit_SSD1306 display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET);

// --- DHT Sensor ---
#define DHTPIN 19
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

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

    setup_wifi();

    client.setServer(mqtt_server, mqtt_port);
    Serial.println("MQTT configurat!");

    if (!display.begin(SSD1306_SWITCHCAPVCC, 0x3C)) {
        Serial.println(F("SSD1306 allocation failed"));
        for (;;); // no continua si falla
    }

    dht.begin();
    display.display();
    delay(2000); // pausa inicial
}

// --- Loop ---
void loop() {
    if (!client.connected()) {
        reconnect();
    }
    client.loop();  // Manté viva la connexió

    static unsigned long lastMsg = 0;
    unsigned long now = millis();

    if (now - lastMsg > 5000) {
        lastMsg = now;

        // --- Dades ---
        float temp = 20;  // pots reemplaçar amb dht.readTemperature()
        float hum = 80;   // pots reemplaçar amb dht.readHumidity()

        // --- Actualitza Pantalla OLED ---
        display.clearDisplay();
        display.setCursor(0, 0);
        display.println("Temperatura: ");
        display.print(temp);
        display.display();

        Serial.println("Display actualitzat");

        // --- Envia JSON per MQTT ---
        char payload[100];
        snprintf(payload, sizeof(payload), "{\"temperatura\": %.2f, \"humitat\": %.0f}", temp, hum);

        Serial.print("Enviant JSON: ");
        Serial.println(payload);

        if (client.publish("casa/", payload)) {
            Serial.println("JSON publicat correctament!");
        } else {
            Serial.println("Error publicant JSON!");
        }
    }
}
