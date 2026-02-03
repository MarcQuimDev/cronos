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

    private function sensorPage(Request $request, string $field, string $viewName, string $varName, string $title)
    {
        $validated = $request->validate([
            'page' => 'sometimes|integer|min:1|max:10000',
            'date' => 'sometimes|date_format:Y-m-d',
        ]);

        try {
            $selectedDate = $request->input('date');

            if ($selectedDate) {
                // When date selected: get ALL data for that day (no pagination) for full chart
                $chartData = SensorData::whereNotNull($field)
                    ->whereDate('timestamp', $selectedDate)
                    ->orderBy('timestamp', 'asc')
                    ->get();
            } else {
                $chartData = collect();
            }

            // Table data only shown when date is selected
            if ($selectedDate) {
                $tableData = SensorData::whereNotNull($field)
                    ->whereDate('timestamp', $selectedDate)
                    ->orderBy('timestamp', 'desc')
                    ->paginate(50)
                    ->appends(['date' => $selectedDate]);
            } else {
                $tableData = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 50);
            }

            $viewData = [
                $varName => $tableData,
                'chartData' => $chartData,
                'selectedDate' => $selectedDate,
            ];

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'title' => $title,
                    'content' => view("partials.{$viewName}-content", $viewData)->render()
                ]);
            }

            // Non-AJAX: redirect to dashboard with page indicator so the main layout always loads
            return redirect("/?load={$viewName}");
        } catch (\Exception $e) {
            \Log::error("Error loading {$viewName} data: " . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => "Error carregant les dades"
                ], 500);
            }

            return back()->with('error', "Error carregant les dades");
        }
    }

    public function temperature(Request $request)
    {
        return $this->sensorPage($request, 'temperatura', 'temperature', 'temperatureData', 'Dades de Temperatura');
    }

    public function humidity(Request $request)
    {
        return $this->sensorPage($request, 'humitat', 'humidity', 'humidityData', 'Dades d\'Humitat');
    }

    public function pressure(Request $request)
    {
        return $this->sensorPage($request, 'pressio', 'pressure', 'pressureData', 'Dades de Pressió');
    }

    public function brightness(Request $request)
    {
        return $this->sensorPage($request, 'brillantor', 'brightness', 'brightnessData', 'Dades de Brillantor');
    }

    public function co2(Request $request)
    {
        return $this->sensorPage($request, 'eco2', 'co2', 'co2Data', 'Dades de CO2');
    }

    public function tvoc(Request $request)
    {
        return $this->sensorPage($request, 'tvoc', 'tvoc', 'tvocData', 'Dades de TVOC');
    }
}
