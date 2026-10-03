<?php

namespace Tests\Unit;

use App\Support\OpeningHours;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class OpeningHoursTest extends TestCase
{
    private function at(string $dateTime): CarbonImmutable
    {
        return CarbonImmutable::parse($dateTime, 'Europe/Berlin');
    }

    public function test_weekday_is_open_at_lunch_and_dinner_but_not_in_between(): void
    {
        $hours = new OpeningHours;

        $this->assertFalse($hours->isOpenAt($this->at('2026-10-07 11:29')));
        $this->assertTrue($hours->isOpenAt($this->at('2026-10-07 11:30')));
        $this->assertTrue($hours->isOpenAt($this->at('2026-10-07 14:59')));
        $this->assertFalse($hours->isOpenAt($this->at('2026-10-07 15:00')));
        $this->assertTrue($hours->isOpenAt($this->at('2026-10-07 21:59')));
        $this->assertFalse($hours->isOpenAt($this->at('2026-10-07 22:00')));
    }

    public function test_saturday_is_only_open_in_the_evening(): void
    {
        $hours = new OpeningHours;

        $this->assertFalse($hours->isOpenAt($this->at('2026-10-10 12:00')));
        $this->assertTrue($hours->isOpenAt($this->at('2026-10-10 18:00')));
    }

    public function test_public_holidays_use_holiday_hours(): void
    {
        $hours = new OpeningHours;

        // 2026-12-25 is a Friday.
        $this->assertSame([['17:00', '22:00']], $hours->rangesFor($this->at('2026-12-25 12:00')));
        $this->assertFalse($hours->isOpenAt($this->at('2026-12-25 12:00')));
        $this->assertTrue($hours->isOpenAt($this->at('2026-12-25 18:00')));
    }

    public function test_time_is_evaluated_in_the_restaurant_timezone(): void
    {
        // 10:00 UTC is 12:00 in Berlin (summer time).
        $this->assertTrue((new OpeningHours)->isOpenAt(CarbonImmutable::parse('2026-07-01 10:00', 'UTC')));
    }

    public function test_nrw_holidays_including_easter_based_ones(): void
    {
        $this->assertSame([
            '2026-01-01',
            '2026-04-03', // Karfreitag
            '2026-04-06', // Ostermontag
            '2026-05-01',
            '2026-05-14', // Christi Himmelfahrt
            '2026-05-25', // Pfingstmontag
            '2026-06-04', // Fronleichnam
            '2026-10-03',
            '2026-11-01',
            '2026-12-25',
            '2026-12-26',
        ], (new OpeningHours)->holidays(2026));

        $this->assertContains('2027-03-26', (new OpeningHours)->holidays(2027)); // Karfreitag 2027
    }
}
