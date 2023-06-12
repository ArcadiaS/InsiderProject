<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexCompetitionWeekRequest;
use App\Http\Resources\CompetitionWeekResource;
use App\Models\CompetitionWeek;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompetitionWeekController extends Controller
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
