<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchWeek extends Model
{
    use HasFactory;

    protected $fillable = [
      'week_number'
    ];

    public function matches(): HasMany
    {
        return $this->hasMany(Competition::class);
    }
}
