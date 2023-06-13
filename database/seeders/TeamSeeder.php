<?php

namespace Database\Seeders;

use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $league = League::query()->where('name', 'Premier League')->firstOrFail();

        $teams = [
         [
             'name' => 'Manchester City',
             'power' => 90,
             'league_id' => $league->id
         ],
         [
             'name' => 'Arsenal',
             'power' => 85,
             'league_id' => $league->id
         ],
         [
             'name' => 'Liverpool',
             'power' => 90,
             'league_id' => $league->id
         ],
         [
             'name' => 'Manchester United',
             'power' => 85,
             'league_id' => $league->id
         ],
         [
             'name' => 'Newcastle United',
             'power' => 80,
             'league_id' => $league->id
         ],
         [
             'name' => 'Aston Villa',
             'power' => 75,
             'league_id' => $league->id
         ],
         [
             'name' => 'Chelsea',
             'power' => 75,
             'league_id' => $league->id
         ],
         [
             'name' => 'Everton',
             'power' => 60,
             'league_id' => $league->id
         ],
        ];

        foreach ($teams as $team){
            Team::query()->updateOrCreate($team);
        }
    }
}
