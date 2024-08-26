<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected $baseUrl = 'https://api.themoviedb.org/3';
    protected $apiKey;
    protected $accessToken;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->accessToken = config('services.tmdb.access_token');
    }

    public function getPopularMovies($page = 1)
    {
        $response = Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/movie/popular", [
                'page' => $page,
            ]);

        return $response->json();
    }

    public function getMovieDetails($movieId)
    {
        $response = Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/movie/{$movieId}");

        return $response->json();
    }

    public function searchMovies($query, $page = 1)
    {
        $response = Http::withToken($this->accessToken)
            ->get("{$this->baseUrl}/search/movie", [
                'query' => $query,
                'page' => $page,
            ]);

        return $response->json();
    }
}