<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'tmdb_id',
        'title',
        'description',
        'release_year',
        'genre',
        'image_url',
        'popularity',
        'vote_average',
        'original_language',
    ];
}