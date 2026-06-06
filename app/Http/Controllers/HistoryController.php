<?php

namespace App\Http\Controllers;

use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function filter_history(Request $request)
    {
        $validated = $request->validate([
            'year' => 'nullable|integer|min:2000|max:' . now()->year,
            'month' => 'nullable|integer|between:1,12',
            'date' => 'nullable|integer|between:1,31',
        ]);

        $year = $validated['year'] ?? null;
        $month = $validated['month'] ?? null;
        $date = $validated['date'] ?? null;

        if (!$year && !$month && !$date) {
            return view('history', [
                'histories' => collect(),
            ]);
        }

        $query = History::query()
            ->when($year, fn($q) => $q->whereYear('date', $year))
            ->when($month, fn($q) => $q->whereMonth('date', $month))
            ->when($date, fn($q) => $q->whereDay('date', $date));

        $histories = $query->paginate(31);

        return view('history', compact('histories'));
    }

    public function store_report(Request $request)
    {
        if (auth()->user()->role !== 'technician') {
            abort(403);
        }

        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',

            'fuel_start' => 'nullable|numeric|min:0',
            'fuel_end' => 'nullable|numeric|min:0',
            'fuel_additional' => 'nullable|numeric|min:0',
            'fuel_resupply' => 'nullable|numeric|min:0',

            'cssd_start' => 'nullable|date_format:H:i',
            'cssd_end' => 'nullable|date_format:H:i|after_or_equal:cssd_start',

            'laundry_start' => 'nullable|date_format:H:i',
            'laundry_end' => 'nullable|date_format:H:i|after_or_equal:laundry_start',
        ], [
            'date.required' => 'Date is required.',
            'date.date_format' => 'Date must be in YYYY-MM-DD format.',
        ]);

        $data = array_filter($validated, function ($value) {
            return $value !== null && $value !== '';
        });

        foreach (['cssd_start', 'cssd_end', 'laundry_start', 'laundry_end'] as $field) {
            if (!empty($data[$field])) {
                $data[$field] .= ':00';
            }
        }

        $history = History::updateOrCreate(
            ['date' => $validated['date']],
            $data
        );

        $startCandidates = collect([
            $history->hour_start,
            $data['cssd_start'] ?? null,
            $data['laundry_start'] ?? null,
        ])->filter();

        if ($startCandidates->isNotEmpty()) {
            $history->hour_start = $startCandidates->min();
        }

        $endCandidates = collect([
            $history->hour_end,
            $data['cssd_end'] ?? null,
            $data['laundry_end'] ?? null,
        ])->filter();

        if ($endCandidates->isNotEmpty()) {
            $history->hour_end = $endCandidates->max();
        }

        $history->save();

        return redirect('/report')->with('success', 'Report Saved.');
    }
}
