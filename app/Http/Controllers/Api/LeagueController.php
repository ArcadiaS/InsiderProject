<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeagueResource;
use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeagueController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $leagues = League::query()->get();

        return LeagueResource::collection($leagues);
    }
}