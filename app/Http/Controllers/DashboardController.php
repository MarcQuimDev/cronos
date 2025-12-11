<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get latest temperature reading (using first() instead of limit(1)->get())
            $temperatureData = SensorData::whereNotNull('temperatura')
                ->orderBy('timestamp', 'desc')
                ->first();

            // Get latest humidity reading
            $humidityData = SensorData::whereNotNull('humitat')
                ->orderBy('timestamp', 'desc')
                ->first();

            // Get latest pressure reading
            $pressureData = SensorData::whereNotNull('pressio')
                ->orderBy('timestamp', 'desc')
                ->first();

            // If AJAX request, return only content partial with title
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => 'Cronos TDR - Tauler de Sensors',
                    'content' => view('partials.dashboard-content', compact('temperatureData', 'humidityData', 'pressureData'))->render()
                ]);
            }

            return view('dashboard', compact('temperatureData', 'humidityData', 'pressureData'));
        } catch (\Exception $e) {
            \Log::error('Error loading dashboard: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Error carregant les dades del tauler'
                ], 500);
            }

            return back()->with('error', 'Error carregant les dades del tauler');
        }
    }

    public function temperature(Request $request)
    {
        try {
            // Get paginated temperature data (50 per page for better performance)
            $temperatureData = SensorData::whereNotNull('temperatura')
                ->orderBy('timestamp', 'desc')
                ->paginate(50);

            // If AJAX request, return only content partial with title
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => 'Dades de Temperatura',
                    'content' => view('partials.temperature-content', compact('temperatureData'))->render()
                ]);
            }

            return view('temperature', compact('temperatureData'));
        } catch (\Exception $e) {
            \Log::error('Error loading temperature data: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Error carregant les dades de temperatura'
                ], 500);
            }

            return back()->with('error', 'Error carregant les dades de temperatura');
        }
    }

    public function humidity(Request $request)
    {
        try {
            // Get paginated humidity data (50 per page for better performance)
            $humidityData = SensorData::whereNotNull('humitat')
                ->orderBy('timestamp', 'desc')
                ->paginate(50);

            // If AJAX request, return only content partial with title
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => 'Dades d\'Humitat',
                    'content' => view('partials.humidity-content', compact('humidityData'))->render()
                ]);
            }

            return view('humidity', compact('humidityData'));
        } catch (\Exception $e) {
            \Log::error('Error loading humidity data: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Error carregant les dades d\'humitat'
                ], 500);
            }

            return back()->with('error', 'Error carregant les dades d\'humitat');
        }
    }

    public function pressure(Request $request)
    {
        try {
            // Get paginated pressure data (50 per page for better performance)
            $pressureData = SensorData::whereNotNull('pressio')
                ->orderBy('timestamp', 'desc')
                ->paginate(50);

            // If AJAX request, return only content partial with title
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => 'Dades de Pressió',
                    'content' => view('partials.pressure-content', compact('pressureData'))->render()
                ]);
            }

            return view('pressure', compact('pressureData'));
        } catch (\Exception $e) {
            \Log::error('Error loading pressure data: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Error carregant les dades de pressió'
                ], 500);
            }

            return back()->with('error', 'Error carregant les dades de pressió');
        }
    }
}
