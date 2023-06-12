<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'is_active',
      'season_id',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class)->withDefault([
           'name' => Carbon::now()->year.'/'.Carbon::now()->addYear()->year
        ]);
    }
}
