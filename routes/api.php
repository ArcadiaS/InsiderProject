<?php

use App\Http\Controllers\Api\CompetitionController;
use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\SeasonController;
use App\Http\Controllers\Api\CompetitionWeekController;
use App\Http\Controllers\Api\SimulationController;
use App\Http\Controllers\Api\StandingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('seasons', SeasonController::class)->only('index');
Route::apiResource('leagues', LeagueController::class)->only('index');
Route::apiResource('competitions', CompetitionController::class)->only('index');
Route::apiResource('competition-weeks', CompetitionWeekController::class)->only('index');
Route::apiResource('standings', StandingController::class);
Route::post('prepare-league-schedule', [SimulationController::class, 'prepareLeagueSchedule']);
Route::post('play-all-weeks', [SimulationController::class, 'playAllWeeks']);
Route::post('play-week-by-week', [SimulationController::class, 'playWeekByWeek']);
Route::post('reset-all-data', [SimulationController::class, 'resetAllCompetitions']);