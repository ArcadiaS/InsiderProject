<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexStandingRequest;
use App\Http\Resources\StandingResource;
use App\Models\League;
use Illuminate\Http\Request;

class StandingController extends Controller
{
    public function index(IndexStandingRequest $request)
    {
        /** @var League $league */
        $league = League::query()
            ->where('id', $request->get('league_id'))
            ->where('season_id', $request->get('season_id'))
            ->firstOrFail();

        return StandingResource::make(Helper::calculateStandingsUpToWeek($league, $request->get('week_number')));
    }
}
