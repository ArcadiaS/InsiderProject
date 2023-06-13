<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,
          'home_team' => $this->home_team,
          'away_team' => $this->away_team,
          'home_team_goals' => $this->home_team_goals,
          'away_team_goals' => $this->away_team_goals,
          'match_date' => $this->match_date,
          'is_played' => $this->is_played,
        ];
    }
}
