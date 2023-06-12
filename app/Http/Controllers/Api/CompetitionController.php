<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexCompetitionWeekRequest;
use App\Http\Resources\CompetitionWeekResource;
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
}
