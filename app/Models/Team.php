<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
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
}
