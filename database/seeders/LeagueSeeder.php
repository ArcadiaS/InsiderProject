<?php

namespace Database\Seeders;

use App\Models\League;
use App\Models\Season;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeagueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $season1 = Season::query()->create([
            'name' => '2023/24',
            'is_active' => true,
        ]);

        $season2 = Season::query()->create([
            'name' => '2023/24',
            'is_active' => true,
        ]);

        $premierLeague = League::query()->updateOrCreate([
            'name' => 'Premier League',
            'current_week' => 1,
            'season_id' => $season1->id,
        ]);

        $FACup = League::query()->updateOrCreate([
            'name' => 'FA Cup',
            'current_week' => 1,
            'season_id' => $season2->id,
        ]);
    }
}
