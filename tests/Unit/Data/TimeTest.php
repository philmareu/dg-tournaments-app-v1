<?php

namespace Tests\Unit\Data;

use DGTournaments\Data\Time;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TimeTest extends TestCase
{
    /**
     * @test
     */
    public function seconds_are_returned_in_seconds()
    {
        $this->assertEquals(150, Time::make()->seconds(100)->seconds(50)->inSeconds());
    }

    /**
     * @test
     */
    public function returns_seconds_from_minutes()
    {
        $this->assertEquals(90, Time::make()->minutes(1.5)->inSeconds());
    }

    /**
     * @test
     */
    public function should_return_seconds_from_hours()
    {
        $this->assertEquals(5400, Time::make()->hours(1.5)->inSeconds());
    }

    /**
     * @test
     */
    public function should_return_minutes_from_seconds()
    {
        $this->assertEquals(1, Time::make()->seconds(60)->inMinutes());
    }

    /**
     * @test
     */
    public function should_return_minutes_from_hours()
    {
        $this->assertEquals(60, Time::make()->hours(1)->inMinutes());
    }
}
