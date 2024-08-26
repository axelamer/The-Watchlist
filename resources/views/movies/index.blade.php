<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Most Popular Movies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('movies.index') }}" method="GET" class="mb-4">
                        <div class="flex items-center">
                            <input type="text" name="query" placeholder="Search movies..." value="{{ request('query') }}" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                            <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Search
                            </button>
                        </div>
                    </form>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach ($movies as $movie)
                            <div class="movie-card">
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}" class="w-full h-auto rounded-lg shadow-md">
                                <h3 class="mt-2 text-sm font-semibold truncate">{{ $movie['title'] }}</h3>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</p>
                                @if (isset($movie['in_watchlist']) && $movie['in_watchlist'])
                                    <button class="remove-from-watchlist mt-2 text-xs text-red-500" data-tmdb-id="{{ $movie['id'] }}">Remove from Watchlist</button>
                                @else
                                    <button class="add-to-watchlist mt-2 text-xs text-green-500" data-tmdb-id="{{ $movie['id'] }}">Add to Watchlist</button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $movies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded');
            document.querySelectorAll('.add-to-watchlist, .remove-from-watchlist').forEach(button => {
                console.log('Button found:', button);
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    console.log('Button clicked');
                    const tmdbId = this.dataset.tmdbId;
                    const isAdding = this.classList.contains('add-to-watchlist');
                    const url = isAdding ? '/watchlist/add' : '/watchlist/remove';
                    const method = isAdding ? 'POST' : 'DELETE';

                    console.log(`Attempting to ${isAdding ? 'add' : 'remove'} movie with TMDB ID: ${tmdbId}`);

                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ tmdb_id: tmdbId })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response from server:', data);
                        if (data.success) {
                            if (isAdding) {
                                this.textContent = 'Remove from Watchlist';
                                this.classList.remove('add-to-watchlist', 'text-green-500');
                                this.classList.add('remove-from-watchlist', 'text-red-500');
                            } else {
                                this.textContent = 'Add to Watchlist';
                                this.classList.remove('remove-from-watchlist', 'text-red-500');
                                this.classList.add('add-to-watchlist', 'text-green-500');
                            }
                            alert(isAdding ? 'Movie added to watchlist successfully!' : 'Movie removed from watchlist successfully!');
                        } else {
                            alert('Error updating watchlist. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>