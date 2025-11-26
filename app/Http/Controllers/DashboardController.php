<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get latest temperature reading
        $temperatureData = SensorData::whereNotNull('temperatura')
            ->orderBy('timestamp', 'desc')
            ->limit(1)
            ->get();

        // Get latest humidity reading
        $humidityData = SensorData::whereNotNull('humitat')
            ->orderBy('timestamp', 'desc')
            ->limit(1)
            ->get();

        // Get latest pressure reading
        $pressureData = SensorData::whereNotNull('pressio')
            ->orderBy('timestamp', 'desc')
            ->limit(1)
            ->get();

        return view('dashboard', compact('temperatureData', 'humidityData', 'pressureData'));
    }

    public function temperature()
    {
        // Get all temperature data
        $temperatureData = SensorData::whereNotNull('temperatura')
            ->orderBy('timestamp', 'desc')
            ->get();

        return view('temperature', compact('temperatureData'));
    }

    public function humidity()
    {
        // Get all humidity data
        $humidityData = SensorData::whereNotNull('humitat')
            ->orderBy('timestamp', 'desc')
            ->get();

        return view('humidity', compact('humidityData'));
    }

    public function pressure()
    {
        // Get all pressure data
        $pressureData = SensorData::whereNotNull('pressio')
            ->orderBy('timestamp', 'desc')
            ->get();

        return view('pressure', compact('pressureData'));
    }
}
