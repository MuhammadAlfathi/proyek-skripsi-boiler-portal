<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    /** @use HasFactory<\Database\Factories\HistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'date',
        'fuel_start',
        'fuel_end',
        'fuel_additional',
        'fuel_resupply',
        'cssd_start',
        'cssd_end',
        'laundry_start',
        'laundry_end',
        'hour_start',
        'hour_end'
    ];

    public $timestamps = true;

    protected $casts = [
        'date' => 'date',
        'fuel_consumption' => 'integer',
        'hour_duration' => 'integer'
    ];

    public function isHoliday(): bool
    {
        $year = $this->date->year;
        $holidays = config("holidays.$year", []);

        return in_array($this->date->format('Y-m-d'), $holidays);
    }

    public function isNonWorkingDay(): bool
    {
        return $this->date->isWeekend() || $this->isHoliday();
    }
}