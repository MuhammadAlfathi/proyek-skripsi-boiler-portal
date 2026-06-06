<div class="p-4 bg-green/5 rounded-xl">
    <form action="{{ route('history.filter') }}" 
          method="GET" 
          class="flex items-center gap-4">

        <div class="flex items-center gap-3">
            <select name="year"
                class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green">
                <option value="">-- Year --</option>
                @for ($y = 2025; $y <= now()->year; $y++)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <select name="month"
                class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green">
                <option value="">-- Month --</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endfor
            </select>

            <select name="date"
                class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green">
                <option value="">-- Date --</option>
                @for ($d = 1; $d <= 31; $d++)
                <option value="{{ $d }}" {{ request('date') == $d ? 'selected' : '' }}>
                        {{ $d }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="ml-auto">
            <button type="submit"
                class="px-4 py-1.5 bg-green text-white rounded-md text-sm font-semibold 
                       hover:bg-green/80 transition-colors duration-300">
                View
            </button>
        </div>

    </form>
</div>