<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Helper;
use App\Models\Competition;
use App\Models\League;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelperTest extends TestCase
{
    use RefreshDatabase;

    public function testRoundRobinAlgorithm(): void
    {
        $league = League::factory()->create();
        $teams = Team::factory()->count(4)->create(['league_id' => $league->id]);

        $result = Helper::roundRobinAlgorithm($league);

        $this->assertTrue($result);
        $this->assertCount(6, Competition::all());
    }

    public function testCalculateStandingsUpToWeek(): void
    {
        $league = League::factory()->create();
        $teams = Team::factory()->count(4)->create(['league_id' => $league->id]);

        Helper::roundRobinAlgorithm($league);
        Competition::query()->first()->update([
           'is_played' => true
        ]);

        $standings = Helper::calculateStandingsUpToWeek($league, 1);
        $this->assertCount(4, $standings);
    }

    public function testSimulateMatch(): void
    {
        $team1 = Team::factory()->make(['power' => 50]);
        $team2 = Team::factory()->make(['power' => 50]);

        $result = Helper::simulateMatch($team1, $team2);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertIsInt($result[0]);
        $this->assertIsInt($result[1]);
    }

}
