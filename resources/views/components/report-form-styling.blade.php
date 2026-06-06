@props(['type' => null, 'name' => null, 'placeholder' => null,])

@if ($type === 'date')
  <div class="sm:col-span-3">
    <label  for="{{ $name }}" 
            class="inline-flex items-center gap-x-2 font-bold text-lg text-gray-900">
          <span class="w-2 h-2 bg-green inline-block"></span>  
          {{ $slot }}
    </label>
    <div class="mt-2">
      <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-green focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600 w-full">
        <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $attributes }} class="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
      </div>
    </div>
  </div>
@else
  <div class="sm:col-span-3">
    <label  for="{{ $name }}" 
            class="inline-flex items-center gap-x-2 font-bold text-lg text-gray-900">
          <span class="w-2 h-2 bg-green inline-block"></span>  
          {{ $slot }}
    </label>
    <div class="mt-2">
      <div class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-green focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600 w-full">
        <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" placeholder="{{ $placeholder }}" class="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
      </div>
    </div>
  </div>
@endif