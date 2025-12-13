<?php

namespace Database\Seeders;

use App\Models\SensorData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SensorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Locations for sensors
        $locations = ['Oficina Principal', 'Sala de Servidores', 'Almacén', 'Laboratorio', 'Recepción'];

        // Generate sample data for the last 7 days
        for ($i = 0; $i < 100; $i++) {
            SensorData::create([
                'topic' => 'sensors/environmental/' . rand(1, 5),
                'sensor_type' => 'BME680',
                'temperatura' => rand(150, 350) / 10, // 15.0 to 35.0
                'humitat' => rand(300, 800) / 10, // 30.0 to 80.0
                'pressio' => rand(9500, 10500) / 10, // 950.0 to 1050.0 hPa
                'brillantor' => rand(0, 1000) / 10, // 0.0 to 100.0%
                'eco2' => rand(400, 2000), // 400 to 2000 ppm
                'tvoc' => rand(0, 2000), // 0 to 2000 ppb
                'location' => $locations[array_rand($locations)],
                'timestamp' => now()->subMinutes(rand(0, 10080))->format('Y-m-d H:i:s'), // Random time in last 7 days
            ]);
        }
    }
}
