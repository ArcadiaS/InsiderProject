<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeagueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'current_week'        => $this->current_week,
            'season'              => SeasonResource::make($this->whenLoaded('season')),
            'teams'               => TeamResource::collection($this->whenLoaded('teams')),
            'competition_weeks'   => CompetitionWeekResource::collection($this->whenLoaded('competition_weeks')),
            'competition_matches' => CompetitionResource::collection($this->whenLoaded('competition_matches')),
        ];
    }
}
