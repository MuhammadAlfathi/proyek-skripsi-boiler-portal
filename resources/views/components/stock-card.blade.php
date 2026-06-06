@props(['latest', 'calibration'])

<div class="p-4 bg-green/5 rounded-xl flex gap-x-6">
    <div class="flex-1 flex flex-col">
        <div class="self-start text-sm text-gray-600">({{ $latest?->date ?? 'YYYY-MM-DD'}})</div>

        <h3 class="font-bold text-xl">
            @if (optional($latest)->stock_calibration !== null)
                {{ $latest->stock_calibration }} L
            @elseif (optional($latest)->stock_after !== null)
                {{ $latest->stock_after }} L
            @else
                NO DATA
            @endif
        </h3>
    </div>

    @if (auth()->user()->role === 'technician')
        <div class="flex flex-col items-end">        
            <a href="{{ route('calibration') }}" 
                class="px-3 py-1 bg-green text-white rounded-sm text-sm font-semibold hover:bg-green/80 transition-colors duration-300">
                Calibrate
            </a>
            <p class="text-sm text-gray-600">Last Calibration : {{ $calibration }}</p>
        </div>
    @else
        <div class="flex flex-col items-end">        
            <p class="text-sm text-gray-600">Last Calibration : {{ $calibration }}</p>
        </div>
    @endif
</div>