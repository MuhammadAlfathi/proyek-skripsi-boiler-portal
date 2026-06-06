<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\History;
use App\Models\Stock;

class ForecastService
{
    public function forecastConsumptionForDate(Carbon $date): ?float
    {
        $averageHour = $this->averageHour();
        $mnB = $this->calcMnB();

        if (
            $averageHour['hourWeekday'] === null ||
            $averageHour['hourWeekend'] === null ||
            $mnB['m'] === null ||
            $mnB['b'] === null
        ) {
            return null;
        }

        $weekdayForecast = $mnB['m'] * $averageHour['hourWeekday'] + $mnB['b'];
        $weekendForecast = $mnB['m'] * $averageHour['hourWeekend'] + $mnB['b'];

        return ($date->isWeekend() || $this->isHoliday($date))
            ? $weekendForecast
            : $weekdayForecast;
    }

    public function averageHour()
    {
        $latest = $this->getLatestData();

        $latestWeekday = $latest->filter(
            fn($item) =>
            $item->date->isWeekday() && !$this->isHoliday($item->date)
        )->values();

        $latestWeekend = $latest->filter(
            fn($item) =>
            $item->date->isWeekend() || $this->isHoliday($item->date)
        )->values();

        $averageHourWeekday = $latestWeekday->avg('hour_duration');
        $averageHourWeekend = $latestWeekend->avg('hour_duration');

        if ($averageHourWeekday === null && $averageHourWeekend !== null) {
            $averageHourWeekday = $averageHourWeekend;
        }

        if ($averageHourWeekday !== null && $averageHourWeekend === null) {
            $averageHourWeekend = $averageHourWeekday;
        }

        return [
            'hourWeekday' => $averageHourWeekday,
            'hourWeekend' => $averageHourWeekend
        ];
    }

    public function calcMnB()
    {
        $latest = $this->getLatestData();

        $n = $latest->count();

        $sumX = $latest->sum('hour_duration');
        $sumY = $latest->sum('fuel_consumption');

        $sumX2 = $latest->sum(fn($row) => $row->hour_duration ** 2);
        $sumXY = $latest->sum(fn($row) => $row->hour_duration * $row->fuel_consumption);

        $denominator = (($n * $sumX2) - ($sumX ** 2));

        if ($denominator === 0) {
            return [
                'm' => null,
                'b' => null
            ];
        }

        $m = (($n * $sumXY) - ($sumX * $sumY)) / $denominator;

        $b = ($sumY - ($m * $sumX)) / $n;

        return [
            'm' => $m,
            'b' => $b
        ];
    }

    public function getLatestData()
    {
        $latest = History::whereNotNull('fuel_consumption')
            ->whereNotNull('hour_duration')
            ->latest('date')
            ->take(31)
            ->get()
            ->reverse()
            ->values();

        return $latest;
    }


    public function isHoliday(Carbon $date): bool
    {
        $year = $date->year;
        $holidays = config("holidays.$year", []);

        return in_array($date->format('Y-m-d'), $holidays);
    }

    public function forecast()
    {
        $latestStock = Stock::latest('date')->first();
        $averageHour = $this->averageHour();
        $MnB = $this->calcMnB();

        if (!$latestStock || $MnB['m'] === null || $MnB['b'] === null) {
            return [
                'forecast' => [],
                'weekdayForecast' => null,
                'weekendForecast' => null
            ];
        }

        if ($averageHour['hourWeekday'] === null && $averageHour['hourWeekend'] === null) {
            return [
                'forecast' => [],
                'weekdayForecast' => null,
                'weekendForecast' => null
            ];
        }

        $stock = $latestStock->stock_calibration ?? $latestStock->stock_after;
        $date = $latestStock->date->copy();
        $hourWeekday = $averageHour['hourWeekday'];
        $hourWeekend = $averageHour['hourWeekend'];
        $m = $MnB['m'];
        $b = $MnB['b'];

        $weekdayForecast = $m * $hourWeekday + $b;
        $weekendForecast = $m * $hourWeekend + $b;
        $forecast = [];

        while ($stock > 0) {
            $date->addDay();

            $isWeekend = $date->isWeekend();
            $isHoliday = $this->isHoliday($date);
            $isNonWorkingDay = $isWeekend || $isHoliday;

            $stockBefore = $stock;

            $stock -= $isNonWorkingDay
                ? $weekendForecast
                : $weekdayForecast;

            $stockAfter = max(0, $stock);
            $stock = $stockAfter;

            $forecast[] = [
                'date' => $date->copy(),
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'is_weekend' => $isWeekend,
                'is_holiday' => $isHoliday,
                'is_non_working_day' => $isNonWorkingDay
            ];
        }

        return [
            'forecast' => $forecast,
            'weekdayForecast' => $weekdayForecast,
            'weekendForecast' => $weekendForecast
        ];
    }
}