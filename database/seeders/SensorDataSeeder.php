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
                'topic' => 'sensors/temperature/' . rand(1, 5),
                'sensor_type' => 'DHT22',
                'temperatura' => rand(150, 350) / 10, // 15.0 to 35.0
                'humitat' => rand(300, 800) / 10, // 30.0 to 80.0
                'location' => $locations[array_rand($locations)],
                'timestamp' => now()->subMinutes(rand(0, 10080))->format('Y-m-d H:i:s'), // Random time in last 7 days
            ]);
        }
    }
}
