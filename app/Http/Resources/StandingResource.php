<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'team'                => $this->resource['team'],
            'played'              => $this->resource['played'],
            'won'                 => $this->resource['won'],
            'drawn'               => $this->resource['drawn'],
            'lost'                => $this->resource['lost'],
            'goals_for'           => $this->resource['goals_for'],
            'goals_against'       => $this->resource['goals_against'],
            'goal_difference'     => $this->resource['goal_difference'],
            'points'              => $this->resource['points'],
            'championship_chance' => $this->resource['championship_chance'],
        ];
    }
}
