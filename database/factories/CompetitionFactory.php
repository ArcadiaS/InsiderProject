<?php

namespace Database\Factories;

use App\Models\CompetitionWeek;
use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Competition>
 */
class CompetitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'league_id'           => League::factory()->create(),
            'home_team_id'        => Team::factory()->create(),
            'away_team_id'        => Team::factory()->create(),
            'home_team_goals'     => random_int(0, 3),
            'away_team_goals'     => random_int(0, 3),
            'competition_week_id' => CompetitionWeek::factory()->create(),
            'match_date'          => $this->faker->dateTime,
            'is_played'           => 1,
        ];
    }
}
