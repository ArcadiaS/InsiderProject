<?php

namespace App\Http\Controllers\Api;

use App\Events\MatchUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexCompetitionWeekRequest;
use App\Http\Requests\UpdateCompetitionMatchRequest;
use App\Http\Resources\CompetitionWeekResource;
use App\Models\Competition;
use App\Models\CompetitionWeek;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompetitionController extends Controller
{
    public function index(IndexCompetitionWeekRequest $request): AnonymousResourceCollection
    {
        $competitionWeeks = CompetitionWeek::query()
                                           ->where('league_id', $request->get('league_id'));

        if ($request->has('week_number') and $request->filled('week_number')){
            $competitionWeeks = $competitionWeeks->where('week_number', '<=', $request->match_week);
        }

        return CompetitionWeekResource::collection($competitionWeeks->get());
    }

    public function update(UpdateCompetitionMatchRequest $request, Competition $competition)
    {
        $competition->update($request->validated());
        event(new MatchUpdatedEvent($competition));

        return response()->noContent()->setStatusCode(200);
    }
}
