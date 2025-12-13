<?php

namespace Database\Factories;

use App\Models\SensorData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SensorData>
 */
class SensorDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SensorData::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = ['Oficina Principal', 'Sala de Servidores', 'Almacén', 'Laboratorio', 'Recepción'];

        return [
            'topic' => 'sensors/environmental/' . fake()->numberBetween(1, 5),
            'sensor_type' => fake()->randomElement(['BME680', 'DHT22', 'BMP280', 'SHT31']),
            'temperatura' => fake()->randomFloat(2, 15.0, 35.0), // 15.0 to 35.0°C
            'humitat' => fake()->randomFloat(2, 30.0, 80.0), // 30.0 to 80.0%
            'pressio' => fake()->randomFloat(2, 950.0, 1050.0), // 950.0 to 1050.0 hPa
            'brillantor' => fake()->randomFloat(2, 0.0, 100.0), // 0.0 to 100.0%
            'eco2' => fake()->numberBetween(400, 2000), // 400 to 2000 ppm
            'tvoc' => fake()->numberBetween(0, 2000), // 0 to 2000 ppb
            'location' => fake()->randomElement($locations),
            'timestamp' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }

    /**
     * Indicate that the sensor data is for high temperature.
     */
    public function highTemperature(): static
    {
        return $this->state(fn (array $attributes) => [
            'temperatura' => fake()->randomFloat(2, 30.0, 50.0),
        ]);
    }

    /**
     * Indicate that the sensor data is for low temperature.
     */
    public function lowTemperature(): static
    {
        return $this->state(fn (array $attributes) => [
            'temperatura' => fake()->randomFloat(2, -10.0, 20.0),
        ]);
    }

    /**
     * Indicate that the sensor data has high humidity.
     */
    public function highHumidity(): static
    {
        return $this->state(fn (array $attributes) => [
            'humitat' => fake()->randomFloat(2, 60.0, 100.0),
        ]);
    }

    /**
     * Indicate that the sensor data has poor air quality.
     */
    public function poorAirQuality(): static
    {
        return $this->state(fn (array $attributes) => [
            'eco2' => fake()->numberBetween(1200, 2000),
            'tvoc' => fake()->numberBetween(660, 2000),
        ]);
    }

    /**
     * Indicate that the sensor data has good air quality.
     */
    public function goodAirQuality(): static
    {
        return $this->state(fn (array $attributes) => [
            'eco2' => fake()->numberBetween(400, 800),
            'tvoc' => fake()->numberBetween(0, 220),
        ]);
    }
}
