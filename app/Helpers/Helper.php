<?php

namespace App\Helpers;

use App\Models\Competition;
use App\Models\League;
use App\Models\MatchWeek;

class Helper
{
    public static function roundRobinAlgorithm(League $league): bool
    {
        Competition::query()->where('league_id', $league->id)->delete();

        $teams = $league->teams()->pluck('id')->toArray();
        $numTeams = count($teams);
        $matchWeeks = [];

        for ($i = 0; $i < $numTeams - 1; $i++) {
            $matchWeek = [];

            for ($j = 0; $j < $numTeams / 2; $j++) {
                $homeTeam = $teams[$j];
                $awayTeam = $teams[$numTeams - 1 - $j];

                if ($j === 0) {
                    $awayTeam = $teams[$i];
                } else if ($j <= $i) {
                    $temp = $homeTeam;
                    $homeTeam = $awayTeam;
                    $awayTeam = $temp;
                }

                $matchWeek[] = ['home_team_id' => $homeTeam, 'away_team_id' => $awayTeam];
            }

            shuffle($matchWeek);
            $matchWeeks[] = $matchWeek;

            $teams[] = array_shift($teams);
        }

        foreach ($matchWeeks as $weekNumber => $matches) {
            $matchWeek = Matchweek::create(['week_number' => $weekNumber + 1]);

            foreach ($matches as $match) {
                Competition::query()->create([
                    'home_team_id' => $match['home_team_id'],
                    'away_team_id' => $match['away_team_id'],
                    'match_week_id' => $matchWeek->id,
                ]);
            }
        }

        return true;
    }
}