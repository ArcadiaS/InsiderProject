<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexSeasonRequest;
use App\Http\Resources\SeasonResource;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SeasonController extends Controller
{
    public function index(IndexSeasonRequest $request): AnonymousResourceCollection
    {
        $seasons = Season::query()
                         ->with('leagues')
                         ->whereHas('leagues', function ($query) use ($request) {
                             return $query->where('id', $request->get('league_id'));
                         })
                         ->get();

        return SeasonResource::collection($seasons);
    }
}
