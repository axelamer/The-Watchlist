<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $query = $request->get('query', '');
        
        if ($query) {
            $response = $this->tmdbService->searchMovies($query, $page);
        } else {
            $response = $this->tmdbService->getPopularMovies($page);
        }

        $data = $response;
        $movies = $data['results'];

        // Get the user's watchlist
        $user = auth()->user();
        $watchlistMovieIds = $user ? $user->watchlist()->pluck('tmdb_id')->toArray() : [];

        // Add 'in_watchlist' key to each movie
        foreach ($movies as &$movie) {
            $movie['in_watchlist'] = in_array($movie['id'], $watchlistMovieIds);
        }

        $paginator = new LengthAwarePaginator(
            $movies,
            $data['total_results'],
            20,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('movies.index', ['movies' => $paginator, 'query' => $query]);
    }

    public function show($id)
    {
        $movie = Movie::findOrFail($id);
        $tmdbMovie = $this->tmdbService->getMovieDetails($movie->tmdb_id);
        
        $movie->update([
            'description' => $tmdbMovie['overview'],
        ]);

        return view('movies.show', compact('movie', 'tmdbMovie'));
    }

    public function apiIndex(Request $request)
    {
        $page = $request->input('page', 1);
        $movies = Movie::orderBy('popularity', 'desc')
                       ->paginate(20, ['*'], 'page', $page);
        return response()->json($movies->items());
    }

    public function apiShow($id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie);
    }
}