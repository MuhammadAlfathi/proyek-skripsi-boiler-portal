<x-layout>
    <form method="POST" action="/report">
        @csrf
        <div class="space-y-10">
            <div class="border-b border-green pb-12 space-y-10">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-report-form-styling type="date" name="date" required>Date</x-report-form-styling>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-report-form-styling type="number" name="fuel_start" placeholder="1000">Fuel Level - Start</x-report-form-styling>
                    <x-report-form-styling type="number" name="fuel_end" placeholder="550">Fuel Level - End</x-report-form-styling>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-report-form-styling type="number" name="fuel_additional" placeholder="150">Fuel Added</x-report-form-styling>
                    <x-report-form-styling type="number" name="fuel_resupply" placeholder="5000">Fuel Resupply</x-report-form-styling>
                </div>

                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-report-form-styling type="time" name="cssd_start">CSSD Operating Time - Start</x-report-form-styling>
                    <x-report-form-styling type="time" name="cssd_end">CSSD Operating Time - End</x-report-form-styling>
                </div>
                
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-report-form-styling type="time" name="laundry_start">Laundry Operating Time - Start</x-report-form-styling>
                    <x-report-form-styling type="time" name="laundry_end">Laundry Operating Time - End</x-report-form-styling>
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