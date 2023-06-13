<?php

namespace App\Http\Controllers\Api;

use App\Events\MatchPlayedEvent;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlayAllWeeksRequest;
use App\Http\Requests\PlayWeekByWeekRequest;
use App\Http\Requests\PrepareLeagueScheduleRequest;
use App\Http\Requests\ResetAllCompetitionRequest;
use App\Models\Competition;
use App\Models\CompetitionWeek;
use App\Models\League;
use Carbon\Carbon;

class SimulationController extends Controller
{
    public function prepareLeagueSchedule(PrepareLeagueScheduleRequest $request): \Illuminate\Http\Response
    {
        /** @var League $league */
        $league = League::query()
                        ->where('id', $request->get('league_id'))
                        ->whereHas('season', function ($query) {
                            return $query->IsActive();
                        })
                        ->firstOrFail();


            $league->prepareSchedule();


        return response()->noContent();
    }
    public function playAllWeeks(PlayAllWeeksRequest $request): \Illuminate\Http\Response
    {
        /** @var League $league */
        $league = League::query()
                        ->where('id', $request->get('league_id'))
                        ->whereHas('season', function ($query) {
                            return $query->IsActive();
                        })
                        ->firstOrFail();

        $league->competition_weeks()
               ->each(function (CompetitionWeek $competitionWeek) {
                   $this->simulateEachMatch($competitionWeek);
               });

        return response()->noContent()->setStatusCode(200);
    }

    public function playWeekByWeek(PlayWeekByWeekRequest $request): \Illuminate\Http\Response
    {
        /** @var League $league */
        $league = League::query()
                        ->where('id', $request->get('league_id'))
                        ->whereHas('season', function ($query) {
                            return $query->IsActive();
                        })
                        ->firstOrFail();

        /** @var CompetitionWeek $currentWeek */
        $currentWeek = $league->competition_weeks()
                              ->where('is_played', 0)
                              ->orderBy('week_number')
                              ->first();

        $this->simulateEachMatch($currentWeek);


        return response()->noContent()->setStatusCode(200);
    }

    public function resetAllCompetitions(ResetAllCompetitionRequest $request): \Illuminate\Http\Response
    {
        /** @var League $league */
        $league = League::query()
                        ->where('id', $request->get('league_id'))
                        ->whereHas('season', function ($query) {
                            return $query->IsActive();
                        })
                        ->firstOrFail();

        $league->competition_matches()->update([
            'home_team_goals' => 0,
            'away_team_goals' => 0,
            'is_played'       => false
        ]);

        return response()->noContent()->setStatusCode(200);
    }

    /**
     * @param CompetitionWeek $currentWeek
     * @return void
     */
    private function simulateEachMatch(CompetitionWeek $currentWeek): void
    {
        $currentWeek->matches()->each(function (Competition $competition) {
            $simulateMatch = Helper::simulateMatch($competition->home_team, $competition->away_team);
            dd($simulateMatch);
            $competition->update([
                'home_team_goals' => $simulateMatch[0],
                'away_team_goals' => $simulateMatch[1],
                'match_date'      => Carbon::now(),
                'is_played'       => true,
            ]);
            $competition->league()->update([
                'current_week' => $competition->competition_week()->first()?->getAttribute('week_number')
            ]);
            event(new MatchPlayedEvent($competition));
        });
    }
}
