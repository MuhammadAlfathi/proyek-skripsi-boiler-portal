<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Services\ForecastService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

class StockController extends Controller
{
    public function index(ForecastService $forecastService)
    {
        $this->auto_refresh($forecastService);

        $data = $forecastService->forecast();

        return view('forecast', [
            'latest' => $this->latest_stock(),
            'calibration' => $this->latest_calibration(),
            'forecast' => $data['forecast'],
            'weekdayForecast' => $data['weekdayForecast'],
            'weekendForecast' => $data['weekendForecast']
        ]);
    }

    public function calibration(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|date_format:Y-m-d',
            'stock_calibration' => 'required|numeric|min:0'
        ], [
            'date.required' => 'Date is required',
            'date.date_format' => 'Date must be YYYY-MM-DD',
            'stock_calibration.required' => 'Calibrated Fuel Stock is required'
        ]);

        Stock::updateOrCreate(
            ['date' => $validated['date']],
            ['stock_calibration' => $validated['stock_calibration']]
        );

        return redirect('/')->with('success', 'New Calibration Saved.');
    }

    public function latest_stock()
    {
        return DB::table('stocks')
            ->orderBy('date', 'desc')
            ->first();
    }

    public function latest_calibration()
    {
        return DB::table('stocks')
            ->whereNotNull('stock_calibration')
            ->orderBy('date', 'desc')
            ->value('date');
    }

    public function auto_refresh(ForecastService $forecastService)
    {
        $histories = DB::table('histories')
            ->select('date', 'fuel_resupply', 'fuel_consumption')
            ->get()
            ->keyBy('date');

        $minDate = DB::table('histories')->min('date');
        $maxDate = DB::table('histories')->max('date');

        if (!$minDate || !$maxDate) {
            return;
        }

        $period = CarbonPeriod::create($minDate, $maxDate);

        foreach ($period as $day) {
            $date = $day->format('Y-m-d');
            $history = $histories->get($date);

            $consumed = ($history && $history->fuel_consumption !== null)
                ? $history->fuel_consumption
                : $forecastService->forecastConsumptionForDate($day);

            $additional = ($history && $history->fuel_resupply !== null)
                ? $history->fuel_resupply
                : 0;

            DB::table('stocks')->updateOrInsert(
                ['date' => $date],
                [
                    'stock_additional' => $additional,
                    'stock_consumed' => $consumed,
                ]
            );
        }

        $stocks = DB::table('stocks')
            ->orderBy('date')
            ->get()
            ->values();

        $previousAfter = null;

        foreach ($stocks as $stock) {
            $stockBefore = $previousAfter ?? 0;

            if ($stock->stock_calibration !== null) {
                $stockAfter = $stock->stock_calibration;
            } else {
                $stockAfter = $stockBefore
                    + ($stock->stock_additional ?? 0)
                    - ($stock->stock_consumed ?? 0);
            }

            DB::table('stocks')
                ->where('date', $stock->date)
                ->update([
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                ]);

            $previousAfter = $stockAfter;
        }
    }
}
