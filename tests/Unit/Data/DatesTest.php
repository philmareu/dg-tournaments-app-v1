<?php

namespace Tests\Unit\Data;

use Carbon\Carbon;
use DGTournaments\Data\Dates;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatesTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_the_correct_amount_of_day_difference_between_the_days()
    {
        $dates = new Dates(Carbon::now(), Carbon::now()->addDays(3));

        $this->assertEquals(3, $dates->days());
    }

    /**
     * @test
     */
    public function should_return_true_if_dates_are_only_one_day()
    {
        $dates = new Dates(Carbon::createFromFormat('Y-m-d', '2017-1-1'), Carbon::createFromFormat('Y-m-d', '2017-1-1'));

        $this->assertTrue($dates->isOneDay());
    }

    /**
     * @test
     */
    public function should_return_a_formatted_date_span_same_month()
    {
        $dates = new Dates(Carbon::createFromFormat('Y-m-d', '2017-1-3'), Carbon::createFromFormat('Y-m-d', '2017-1-5'));

        $this->assertEquals('Jan 3rd - 5th', $dates->formattedDateSpan());
    }

    /**
     * @test
     */
    public function should_return_a_formatted_date_span_over_two_difference_months()
    {
        $dates = new Dates(Carbon::createFromFormat('Y-m-d', '2017-1-3'), Carbon::createFromFormat('Y-m-d', '2017-2-5'));

        $this->assertEquals('Jan 3rd - Feb 5th', $dates->formattedDateSpan());
    }
}
