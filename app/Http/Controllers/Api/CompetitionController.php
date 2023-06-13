<?php

namespace App\Http\Controllers\Api;

use App\Events\MatchUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexCompetitionWeekRequest;
use App\Http\Requests\UpdateCompetitionMatchRequest;
use App\Http\Resources\CompetitionResource;
use App\Http\Resources\CompetitionWeekResource;
use App\Models\Competition;
use App\Models\CompetitionWeek;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompetitionController extends Controller
{
    public function update(UpdateCompetitionMatchRequest $request, Competition $competition)
    {
        $competition->update($request->validated());
        event(new MatchUpdatedEvent($competition));

        return response()->noContent()->setStatusCode(200);
    }
}
