<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get temperature data (assuming sensor_type differentiates them)
        $temperatureData = SensorData::whereNotNull('temperatura')
            ->orderBy('timestamp', 'desc')
            ->limit(50)
            ->get();

        // Get humidity data
        $humidityData = SensorData::whereNotNull('humitat')
            ->orderBy('timestamp', 'desc')
            ->limit(50)
            ->get();

        return view('dashboard', compact('temperatureData', 'humidityData'));
    }
}
