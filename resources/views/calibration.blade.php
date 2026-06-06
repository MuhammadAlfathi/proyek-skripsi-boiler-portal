<x-layout>
    <form method="POST" action="/calibration">
    @csrf
    <div class="space-y-10">
        <div class="border-b border-green pb-12 space-y-10">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <x-report-form-styling type="date" name="date" required>Date</x-report-form-styling>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <x-report-form-styling type="number" name="stock_calibration" placeholder="5000">Calibrated Fuel Stock (L)</x-report-form-styling>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit"
                class="px-4 py-1.5 bg-green text-white rounded-md text-sm font-semibold 
                    hover:bg-green/80 transition-colors duration-300">
                Save
            </button>
        </div>
    </div>
    </form>
</x-layout>