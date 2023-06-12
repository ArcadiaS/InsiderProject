<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'champion_team_id',
    ];

    protected $casts = [
        'is_active' => 'bool'
    ];

    public function leagues(): HasMany
    {
        return $this->hasMany(League::class);
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function champion_team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
