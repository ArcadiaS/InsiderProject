<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'home_team_goals',
        'away_team_goals',
        'match_week_id',
        'match_date',
    ];

    protected $casts = [
      'match_date' => 'datetime'
    ];

    public function home_team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function away_team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function match_week(): BelongsTo
    {
        return $this->belongsTo(MatchWeek::class);
    }
}
