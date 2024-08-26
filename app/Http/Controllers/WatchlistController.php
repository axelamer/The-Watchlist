<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TmdbService;
use Illuminate\Support\Facades\Log;

class WatchlistController extends Controller
{
    public function index(TmdbService $tmdbService)
    {
        $watchlist = Watchlist::where('user_id', Auth::id())->get();
        
        foreach ($watchlist as $item) {
            $item->movie = $tmdbService->getMovieDetails($item->tmdb_id);
        }
        
        return view('watchlist.index', compact('watchlist'));
    }

    public function add(Request $request)
    {
        Log::info('Watchlist add method called', $request->all());

        try {
            $watchlist = Watchlist::firstOrCreate([
                'user_id' => Auth::id(),
                'tmdb_id' => $request->tmdb_id
            ]);

            Log::info('Movie added to watchlist', [
                'watchlist_id' => $watchlist->id,
                'tmdb_id' => $request->tmdb_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Movie added to watchlist',
                'watchlist_id' => $watchlist->id
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding movie to watchlist', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'tmdb_id' => $request->tmdb_id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error adding movie to watchlist'
            ], 500);
        }
    }

    public function remove(Request $request)
    {
        Watchlist::where('user_id', Auth::id())
            ->where('tmdb_id', $request->tmdb_id)
            ->delete();
        return response()->json(['success' => true]);
    }
}