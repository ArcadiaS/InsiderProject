<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'power',
      'league_id'
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function seasons(): HasManyThrough
    {
        return $this->hasManyThrough(Season::class, League::class);
    }

    public function home_competitions(): HasMany
    {
        return $this->hasMany(Competition::class, 'home_team_id');
    }

    public function away_competitions(): HasMany
    {
        return $this->hasMany(Competition::class, 'away_team');
    }
}
