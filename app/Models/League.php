<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'current_week',
      'season_id',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function competition_matches(): HasMany
    {
        return $this->hasMany(Competition::class);
    }

    public function competition_weeks(): HasMany
    {
        return $this->hasMany(CompetitionWeek::class);
    }

}
