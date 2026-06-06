<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boiler Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-hanken-grotesk pb-20">
    <div class="px-10">
        <nav class="flex justify-between items-center py-4 border-b border-green">
            <div>
                <a href="/">
                    <img src="{{ Vite::asset('resources/images/logo-new.svg') }}" 
                    alt="" 
                    class="h-10 w-auto">
                </a>
            </div>

            @auth
                <div class="space-x-6 font-bold text-black">
                    <a href="/" class="hover:text-green">Forecast</a>
                    @if (auth()->user()->role === 'technician')
                        <a href="/report" class="hover:text-green">Report</a>
                    @endif
                    <a href="/history" class="hover:text-green">History</a>
                </div>

                <form method="POST" action="/logout">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="hover:text-red">Log Out</button>
                </form>
            @endauth
        </nav>

        <main class="mt-10 max-w-[986px] mx-auto">
            {{ $slot }}
        </main>
    </div>
</body>
</html>