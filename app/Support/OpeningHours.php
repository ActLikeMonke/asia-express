<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * Opening hours from config/restaurant.php, including public holidays in NRW.
 */
class OpeningHours
{
    /**
     * Current time in the restaurant's timezone.
     */
    public function now(): CarbonImmutable
    {
        return CarbonImmutable::now(config('restaurant.timezone'));
    }

    /**
     * Opening ranges for the given day as a list of [open, close] ("H:i").
     *
     * @return list<array{string, string}>
     */
    public function rangesFor(CarbonInterface $date): array
    {
        if ($this->isHoliday($date)) {
            return config('restaurant.holiday_hours');
        }

        return config('restaurant.hours')[$date->isoWeekday()] ?? [];
    }

    public function isOpenAt(CarbonInterface $moment): bool
    {
        $moment = $moment->setTimezone(config('restaurant.timezone'));
        $time = $moment->format('H:i');

        foreach ($this->rangesFor($moment) as [$from, $to]) {
            if ($time >= $from && $time < $to) {
                return true;
            }
        }

        return false;
    }

    public function isHoliday(CarbonInterface $date): bool
    {
        return in_array($date->format('Y-m-d'), $this->holidays($date->year), true);
    }

    /**
     * Public holidays in North Rhine-Westphalia as "Y-m-d".
     *
     * @return list<string>
     */
    public function holidays(int $year): array
    {
        $easter = $this->easterSunday($year);

        return [
            "{$year}-01-01", // Neujahr
            $easter->subDays(2)->format('Y-m-d'), // Karfreitag
            $easter->addDay()->format('Y-m-d'), // Ostermontag
            "{$year}-05-01", // Tag der Arbeit
            $easter->addDays(39)->format('Y-m-d'), // Christi Himmelfahrt
            $easter->addDays(50)->format('Y-m-d'), // Pfingstmontag
            $easter->addDays(60)->format('Y-m-d'), // Fronleichnam
            "{$year}-10-03", // Tag der Deutschen Einheit
            "{$year}-11-01", // Allerheiligen
            "{$year}-12-25", // 1. Weihnachtstag
            "{$year}-12-26", // 2. Weihnachtstag
        ];
    }

    /**
     * Easter Sunday (Gregorian calendar, anonymous algorithm).
     */
    private function easterSunday(int $year): CarbonImmutable
    {
        $a = $year % 19;
        $b = intdiv($year, 100);
        $c = $year % 100;
        $d = intdiv($b, 4);
        $e = $b % 4;
        $f = intdiv($b + 8, 25);
        $g = intdiv($b - $f + 1, 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intdiv($c, 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intdiv($a + 11 * $h + 22 * $l, 451);
        $month = intdiv($h + $l - 7 * $m + 114, 31);
        $day = ($h + $l - 7 * $m + 114) % 31 + 1;

        return CarbonImmutable::create($year, $month, $day, 0, 0, 0, config('restaurant.timezone'));
    }
}
