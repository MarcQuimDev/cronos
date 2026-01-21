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

            // Get latest brightness reading
            $brightnessData = SensorData::whereNotNull('brillantor')
                ->orderBy('timestamp', 'desc')
                ->first();

            // Get latest CO2 reading
            $co2Data = SensorData::whereNotNull('eco2')
                ->orderBy('timestamp', 'desc')
                ->first();

            // Get latest TVOC reading
            $tvocData = SensorData::whereNotNull('tvoc')
                ->orderBy('timestamp', 'desc')
                ->first();

            // If AJAX request, return only content partial with title
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => 'Cronos TDR - Tauler de Sensors',
                    'content' => view('partials.dashboard-content', compact('temperatureData', 'humidityData', 'pressureData', 'brightnessData', 'co2Data', 'tvocData'))->render()
                ]);
            }

            return view('dashboard', compact('temperatureData', 'humidityData', 'pressureData', 'brightnessData', 'co2Data', 'tvocData'));
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
        // Validate pagination parameters to prevent abuse
        $validated = $request->validate([
            'page' => 'sometimes|integer|min:1|max:10000',
        ]);

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
        // Validate pagination parameters to prevent abuse
        $validated = $request->validate([
            'page' => 'sometimes|integer|min:1|max:10000',
        ]);

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
        // Validate pagination parameters to prevent abuse
        $validated = $request->validate([
            'page' => 'sometimes|integer|min:1|max:10000',
        ]);

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

    public function brightness(Request $request)
    {
        // Validate pagination parameters to prevent abuse
        $validated = $request->validate([
            'page' => 'sometimes|integer|min:1|max:10000',
        ]);

        try {
            // Get paginated brightness data (50 per page for better performance)
            $brightnessData = SensorData::whereNotNull('brillantor')
                ->orderBy('timestamp', 'desc')
                ->paginate(50);

            // If AJAX request, return only content partial with title
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => 'Dades de Brillantor',
                    'content' => view('partials.brightness-content', compact('brightnessData'))->render()
                ]);
            }

            return view('brightness', compact('brightnessData'));
        } catch (\Exception $e) {
            \Log::error('Error loading brightness data: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Error carregant les dades de brillantor'
                ], 500);
            }

            return back()->with('error', 'Error carregant les dades de brillantor');
        }
    }

    public function co2(Request $request)
    {
        // Validate pagination parameters to prevent abuse
        $validated = $request->validate([
            'page' => 'sometimes|integer|min:1|max:10000',
        ]);

        try {
            // Get paginated CO2 data (50 per page for better performance)
            $co2Data = SensorData::whereNotNull('eco2')
                ->orderBy('timestamp', 'desc')
                ->paginate(50);

            // If AJAX request, return only content partial with title
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => 'Dades de CO2',
                    'content' => view('partials.co2-content', compact('co2Data'))->render()
                ]);
            }

            return view('co2', compact('co2Data'));
        } catch (\Exception $e) {
            \Log::error('Error loading CO2 data: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Error carregant les dades de CO2'
                ], 500);
            }

            return back()->with('error', 'Error carregant les dades de CO2');
        }
    }

    public function tvoc(Request $request)
    {
        // Validate pagination parameters to prevent abuse
        $validated = $request->validate([
            'page' => 'sometimes|integer|min:1|max:10000',
        ]);

        try {
            // Get paginated TVOC data (50 per page for better performance)
            $tvocData = SensorData::whereNotNull('tvoc')
                ->orderBy('timestamp', 'desc')
                ->paginate(50);

            // If AJAX request, return only content partial with title
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => 'Dades de TVOC',
                    'content' => view('partials.tvoc-content', compact('tvocData'))->render()
                ]);
            }

            return view('tvoc', compact('tvocData'));
        } catch (\Exception $e) {
            \Log::error('Error loading TVOC data: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Error carregant les dades de TVOC'
                ], 500);
            }

            return back()->with('error', 'Error carregant les dades de TVOC');
        }
    }
}
