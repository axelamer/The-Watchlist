<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>The Watchlist</title>
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: 'Figtree', sans-serif;
            }
            .bg-image {
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
            .overlay {
                background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
            }
            .modern-button {
                background-color: rgba(255, 255, 255, 1);
                color: #000;
                backdrop-filter: blur(10px);
                transition: all 0.3s ease;
            }
            .modern-button:hover {
                background-color: rgba(255, 255, 255, 0.8);
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="app" class="relative flex items-center justify-center min-h-screen bg-image">
            <div class="overlay absolute inset-0"></div>
            <div class="relative z-10 text-center">
                <h1 class="text-6xl font-bold text-white mb-8">The Watchlist</h1>
                <p class="text-xl text-white mb-8">Your ultimate movie companion</p>
                @if (Route::has('login'))
                    <div class="space-y-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-64 mx-auto px-6 py-3 text-black font-bold rounded-lg modern-button">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="block w-64 mx-auto px-6 py-3 text-black font-bold rounded-lg modern-button">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-64 mx-auto px-6 py-3 text-black font-bold rounded-lg modern-button">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
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
                    document.querySelector('.bg-image').style.backgroundImage = `url('${backgroundImage}')`;
                })
                .catch(error => console.error('Error fetching movie:', error));
        </script>
    </body>
</html>