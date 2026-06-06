<div class="p-4 bg-green/5 rounded-xl flex flex-col text-center">
    <div class="self-start text-sm">{{ $slot }}</div>
    
    <div class="flex justify-center gap-6 items-center py-3">
        <div class="items-center text center px-3 py-1 bg-black/10 rounded-sm">
            CSSD
            <p>{{ number_format($forecast * 3 / 8, 2) }} L</p>
        </div>
        
        <div class="items-center text center px-3 py-1 bg-black/10 rounded-sm">
            Laundry
            <p>{{ number_format($forecast * 5 / 8, 2) }} L</p>        </div>
        
        <div class="items-center text center px-3 py-1 bg-black/10 rounded-sm">
            Total
            <p>{{ number_format($forecast, 2) }} L</p>
        </div>                
    </div>
</div>