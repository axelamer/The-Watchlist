<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'tmdb_id'];
    protected $table = 'watchlists';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}