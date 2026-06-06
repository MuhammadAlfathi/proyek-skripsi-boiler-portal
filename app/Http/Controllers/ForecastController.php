<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Services\ForecastService;

class ForecastController extends Controller
{
    public function forecast(ForecastService $forecastService)
    {
        return $forecastService->forecast();
    }
}