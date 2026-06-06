@props(['histories'])

<div class="p-4 bg-green/5 rounded-xl gap-x-6">
    <!-- Let all your things have their places; let each part of your business have its time. - Benjamin Franklin -->
    
    <table class="w-full border-collapse text center">
        <thead class="bg-black/10">
            <tr>
                <th rowspan="2" class="px-4 py-2 text-center align-middle border-r border-gray-400">Date</th>
                <th colspan="3" class="px-4 py-2 text-center border-b border-gray-400">Fuel Consumption (L)</th>
                <th colspan="2" class="px-4 py-2 text-center border-b border-l border-gray-400">Operating Time (Start-End)</th>
                <th rowspan="2" class="px-4 py-2 text-center align-middle border-l border-gray-400">Operational Duration</th>
            </tr>
            <tr>
                
                <th class="px-4 py-2 text-center">CSSD</th>
                <th class="px-4 py-2 text-center">Laundry</th>
                <th class="px-4 py-2 text-center">Total</th>

                <th class="px-4 py-2 text-center border-l border-gray-400">CSSD</th>
                <th class="px-4 py-2 text-center">Laundry</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $histories as $history)
                <tr class="border-b hover:bg-gray-100 text-center">
                    <td class="px-4 py-2">
                        <span class="{{ $history->isNonWorkingDay() ? 'text-red-600 font-semibold' : 'font-normal' }}">
                            {{ $history->date->format('Y-m-d') }}
                        </span>
                    </td>
                    
                    <td class="px-4 py-2">
                        ~{{ number_format($history->fuel_consumption * 3 / 8, 0) }}
                    </td>
                    <td class="px-4 py-2">
                        ~{{ number_format($history->fuel_consumption * 5 / 8, 0) }}
                    </td>
                    <td class="px-4 py-2">
                        {{ $history->fuel_consumption }}
                    </td>
                    <td class="px-4 py-2">
                        {{ \Carbon\Carbon::parse($history->cssd_start)->format('H:i') }}-{{ \Carbon\Carbon::parse($history->cssd_end)->format('H:i') }}
                    </td>
                    <td class="px-4 py-2">
                        {{ \Carbon\Carbon::parse($history->laundry_start)->format('H:i') }}-{{ \Carbon\Carbon::parse($history->laundry_end)->format('H:i') }}
                    </td>
                    <td class="px-4 py-2">
                        {{ floor($history->hour_duration / 3600) }} Hours
                        {{ floor($history->hour_duration % 3600 / 60) }} Minutes
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        @if ($histories instanceof \Illuminate\Contracts\Pagination\Paginator)
            {{ $histories->appends(request()->query())->links() }}
        @endif
    </div>
</div>