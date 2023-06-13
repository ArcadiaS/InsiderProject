<?php

namespace App\Helpers;

use App\Models\Competition;
use App\Models\League;
use App\Models\CompetitionWeek;
use App\Models\Team;

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

                $matchWeek[] = ['home_team_id' => $homeTeam, 'away_team_id' => $awayTeam];
            }
            shuffle($matchWeek);
            $matchWeeks[] = $matchWeek;

            $slice1 = array_slice($teams, 1, 1);
            $slice2 = array_slice($teams, 2);
            $teams = array_merge([array_shift($teams)], $slice2, $slice1);
        }

        foreach ($matchWeeks as $matchWeek) {
            $returnMatchWeek = [];
            foreach ($matchWeek as $match) {
                $returnMatchWeek[] = [
                    'home_team_id' => $match['away_team_id'],
                    'away_team_id' => $match['home_team_id']
                ];
            }
            shuffle($returnMatchWeek);
            $matchWeeks[] = $returnMatchWeek;
        }

        foreach ($matchWeeks as $weekNumber => $matches) {
            $matchWeek = CompetitionWeek::query()
                                        ->updateOrCreate([
                                            'league_id'   => $league->id,
                                            'week_number' => $weekNumber + 1
                                        ]);

            foreach ($matches as $match) {
                Competition::query()->create([
                    'league_id'           => $league->id,
                    'home_team_id'        => $match['home_team_id'],
                    'away_team_id'        => $match['away_team_id'],
                    'competition_week_id' => $matchWeek->id,
                ]);
            }
        }

        return true;
    }

    public static function calculateStandingsUpToWeek(League $league, int $weekNumber): array
    {
        $teams = Team::where('league_id', $league->id)->get();

        $standings = [];

        foreach ($teams as $team) {
            $standings[$team->id] = [
                'team'                => $team,
                'played'              => 0,
                'won'                 => 0,
                'drawn'               => 0,
                'lost'                => 0,
                'goals_for'           => 0,
                'goals_against'       => 0,
                'goal_difference'     => 0,
                'points'              => 0,
                'championship_chance' => 0,
            ];
        }

        $competitionWeekIds = CompetitionWeek::where('week_number', '<=', $weekNumber)->pluck('id');
        $matches = Competition::whereIn('competition_week_id', $competitionWeekIds)
                              ->where('league_id', $league->id)
                              ->played()
                              ->get();

        foreach ($matches as $match) {
            $homeTeamId = $match->home_team_id;
            $awayTeamId = $match->away_team_id;
            $homeGoals = $match->home_team_goals;
            $awayGoals = $match->away_team_goals;

            $standings[$homeTeamId]['played']++;
            $standings[$awayTeamId]['played']++;

            $standings[$homeTeamId]['goals_for'] += $homeGoals;
            $standings[$awayTeamId]['goals_for'] += $awayGoals;

            $standings[$homeTeamId]['goals_against'] += $awayGoals;
            $standings[$awayTeamId]['goals_against'] += $homeGoals;

            if ($homeGoals > $awayGoals) {
                $standings[$homeTeamId]['won']++;
                $standings[$homeTeamId]['points'] += 3;
                $standings[$awayTeamId]['lost']++;
            } elseif ($homeGoals < $awayGoals) {
                $standings[$awayTeamId]['won']++;
                $standings[$awayTeamId]['points'] += 3;
                $standings[$homeTeamId]['lost']++;
            } else {
                $standings[$homeTeamId]['drawn']++;
                $standings[$awayTeamId]['drawn']++;
                $standings[$homeTeamId]['points']++;
                $standings[$awayTeamId]['points']++;
            }

        }

        foreach ($standings as &$standing) {
            $standing['goal_difference'] = $standing['goals_for'] - $standing['goals_against'];
        }
        unset($standing);

        $totalWeeks = ($league->teams()->count() - 1) * 2;
        $remainingWeeks = $totalWeeks - $weekNumber;
        $totalPointsRemaining = $remainingWeeks * 3;
        $currentLeaderPoints = array_reduce($standings, function ($max, $item) {
            return max($max, $item['points']);
        }, 0);

        foreach ($standings as &$standing) {
            $maxPossiblePoints = $standing['points'] + $totalPointsRemaining;
            $mathematicallyPossible = $maxPossiblePoints >= $currentLeaderPoints;

            if (!$mathematicallyPossible) {
                $standing['championship_chance'] = 0;
                continue;
            }

            if ($remainingWeeks === 0 && $standing['team']->id !== collect(array_values($standings))->sortBy([['points', 'desc'], ['goal_difference', 'desc']])->first()['team']['id']){
                $standing['championship_chance'] = 0;
                continue;
            }

            $pointDifference = $currentLeaderPoints - $standing['points'];
            $pointsFactor = $standing['points'] / ( $currentLeaderPoints + exp($pointDifference)) ;

            $goalDifferenceFactor = $standing['goal_difference'] > 0 ? 0.1 : 0;
            $teamPower = $standing['team']['power'] / 100;

            $chance = 0;

            if ($pointsFactor){
                $chance += (0.55 * $pointsFactor) + (0.3 * $teamPower);
            }
            if ($goalDifferenceFactor){
                $chance +=  (0.15 * $goalDifferenceFactor) + (0.3 * $teamPower);
            }
            if (!$pointsFactor || !$goalDifferenceFactor){
                $chance = $teamPower;
            }


            $chance = min(1, $chance) * 100;

            $standing['championship_chance'] = round($chance);
        }
        unset($standing);

        $sumOfChances = array_sum(array_column($standings, 'championship_chance'));
        foreach ($standings as &$standing) {
            if ($sumOfChances > 0) {
                $chance = ($standing['championship_chance'] / $sumOfChances) * 100;
                $standing['championship_chance'] = round($chance);
            }
        }
        unset($standing);

        usort($standings, function ($a, $b) {
            if ($a['points'] > $b['points']) {
                return -1;
            }
            if ($a['points'] < $b['points']) {
                return 1;
            }

            if ($a['goal_difference'] > $b['goal_difference']) {
                return -1;
            }
            if ($a['goal_difference'] < $b['goal_difference']) {
                return 1;
            }

            return 0;
        });

        return $standings;
    }

    public static function simulateMatch(Team $homeTeam, Team $awayTeam): array
    {
        $randomFactor1 = random_int(0, 40) - 20;
        $randomFactor2 = random_int(0, 40) - 20;
        $homeAdvantage = random_int(0, 10);

        $team1ExpectedGoals = (($homeTeam->getAttribute('power') + $randomFactor1 + $homeAdvantage - 25) / 20);
        $team2ExpectedGoals = (($awayTeam->getAttribute('power') + $randomFactor2 - 25) / 20);

        $team1Goals = max(0, (int)($team1ExpectedGoals));
        $team2Goals = max(0, (int)($team2ExpectedGoals));

        return [$team1Goals, $team2Goals];
    }


}