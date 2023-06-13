<?php

namespace App\Models;

use App\Helpers\Helper;
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

    public function current_week_competitions()
    {
        return $this->hasMany(Competition::class)->whereHas('competition_week', function ($query){
           return $query->where('week_number', $this->current_week);
        });
    }

    public function prepareSchedule(): void
    {
        Helper::roundRobinAlgorithm($this);
    }

}
