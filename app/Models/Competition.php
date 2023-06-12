<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'league_id',
        'home_team_id',
        'away_team_id',
        'home_team_goals',
        'away_team_goals',
        'competition_week_id',
        'match_date',
        'is_played',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'is_played'     => 'bool',
    ];

    public function scopePlayed($query)
    {
        return $query->where('is_played', 1);
    }

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function home_team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function away_team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function competition_week(): BelongsTo
    {
        return $this->belongsTo(CompetitionWeek::class);
    }
}
