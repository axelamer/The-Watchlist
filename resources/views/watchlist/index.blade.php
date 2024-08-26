<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Watchlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach ($watchlist as $item)
                            <div class="movie-card">
                                <img src="https://image.tmdb.org/t/p/w500{{ $item->movie['poster_path'] }}" alt="{{ $item->movie['title'] }}" class="w-full h-auto rounded-lg shadow-md">
                                <h3 class="mt-2 text-sm font-semibold truncate">{{ $item->movie['title'] }}</h3>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->movie['release_date'])->format('Y') }}</p>
                                <button class="remove-from-watchlist mt-2 text-xs text-red-500" data-tmdb-id="{{ $item->tmdb_id }}">Remove from Watchlist</button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.remove-from-watchlist').forEach(button => {
            button.addEventListener('click', function() {
                const tmdbId = this.dataset.tmdbId;
                fetch(`/watchlist/remove`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ tmdb_id: tmdbId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.movie-card').remove();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
    @endpush
</x-app-layout>