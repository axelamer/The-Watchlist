<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div id="app" class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900 bg-cover bg-center">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <div class="flex justify-between items-center mb-6">
                    <a href="/" class="text-2xl font-bold text-gray-900 dark:text-gray-100">The Watchlist</a>
                </div>
                {{ $slot }}
            </div>
        </div>

        <script>
            // Fetch a random popular movie from TMDB API and set it as background
            const API_KEY = '{{ config('services.tmdb.api_key') }}';
            const API_URL = `https://api.themoviedb.org/3/movie/popular?api_key=${API_KEY}&language=en-US&page=1`;

            fetch(API_URL)
                .then(response => response.json())
                .then(data => {
                    const movies = data.results;
                    const randomMovie = movies[Math.floor(Math.random() * movies.length)];
                    const backgroundImage = `https://image.tmdb.org/t/p/original${randomMovie.backdrop_path}`;
                    document.querySelector('#app').style.backgroundImage = `url('${backgroundImage}')`;
                })
                .catch(error => console.error('Error fetching movie:', error));
        </script>
    </body>
</html>