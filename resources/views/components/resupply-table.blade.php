<div class="p-4 bg-green/5 rounded-xl flex gap-x-6">
    <table class="w-full border-collapse text center">
        <thead class="bg-black/10">
            <tr>
                <th rowspan="2" class="px-4 py-2 text-center border-r border-gray-400">Date</th>
                <th colspan="2" class="px-4 py-2 text-center border-b border-gray-400">Fuel Stock (L)</th>
                <th rowspan="2" class="px-4 py-2 text-center border-l border-gray-400">Event / Alert</th>
            </tr>
            <tr>
                <th class="px-4 py-2 text-center">Before Operational</th>
                <th class="px-4 py-2 text-center">After Operational</th>
            </tr>
        </thead>
        <tbody>
            @php
                $refillShown = false;

                $stockByDate = collect($forecast)->keyBy(fn($item) =>
                    $item['date']->format('Y-m-d'));
            @endphp
            @foreach ($forecast as $data)
                <tr class="border-b hover:bg-gray-100 text-center">
                    <td class="px-4 py-2">
                        <span class="{{ $data['is_non_working_day'] ? 'text-red-600 font-semibold' : 'font-normal' }}">
                            {{ $data['date']->format('Y-m-d') }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        {{ number_format($data['stock_before'], 0) }}
                    </td>
                    <td class="px-4 py-2">
                        {{ number_format($data['stock_after'], 0) }}
                    </td>
                    <td>
                        @if (!$refillShown && $data['stock_before'] <= 1150)
                            <span class="text-red-600 font-bold">
                                REFILL DATE
                            </span>
                            @php
                                $refillShown = true;
                            @endphp
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>