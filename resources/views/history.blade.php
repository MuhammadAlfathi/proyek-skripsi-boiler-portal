<x-layout>
    <div class="space-y-10">
        <section>
            <x-section-heading>Filter</x-section-heading>

            <div class="mt-2 space-y-6">
                <x-history-filter/>
            </div>
            
            <div class="mt-6">
                <x-section-heading>History Table</x-section-heading>

            <div class="mt-2 space-y-6">
                <x-history-table :histories="$histories" />
            </div>
            </div>
        </section>
    </div>
</x-layout>