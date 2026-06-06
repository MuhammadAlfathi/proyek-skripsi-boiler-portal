<x-layout>
    <div class="space-y-10">
        <section>
            <x-section-heading>Stock</x-section-heading>

            <div class="mt-2 space-y-6">
                <x-stock-card :latest="$latest" :calibration="$calibration"/>
            </div>
        </section>

        <section>
            <x-section-heading>Forecast</x-section-heading>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                <x-forecast-card :forecast="$weekdayForecast">Weekday</x-forecast-card>
                <x-forecast-card :forecast="$weekendForecast">Weekend / Holiday</x-forecast-card>
            </div>
        </section>

        <section>
            <x-section-heading>Forecast Table</x-section-heading>

            <div class="mt-2 space-y-6">
                <x-resupply-table :forecast="$forecast"></x-resupply-table>
            </div>
        </section>
    </div>
</x-layout>