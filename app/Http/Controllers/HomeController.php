<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Support\OpeningHours;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const SCHEMA_DAYS = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday',
    ];

    public function __invoke(OpeningHours $openingHours): View
    {
        $now = $openingHours->now();
        $logoUrl = Vite::asset('resources/images/logo.jpeg');

        return view('home', [
            'categories' => MenuCategory::query()
                ->whereHas('items')
                ->with('items')
                ->orderBy('sort_order')
                ->get(),
            'todayRanges' => $openingHours->rangesFor($now),
            'isOpen' => $openingHours->isOpenAt($now),
            'logoUrl' => $logoUrl,
            'schema' => $this->schema($logoUrl),
        ]);
    }

    /**
     * schema.org/Restaurant data for JSON-LD, built from config/restaurant.php only.
     *
     * @return array<string, mixed>
     */
    private function schema(string $logoUrl): array
    {
        $hours = [];

        foreach (config('restaurant.hours') as $day => $ranges) {
            foreach ($ranges as [$from, $to]) {
                $hours[] = [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => 'https://schema.org/'.self::SCHEMA_DAYS[$day],
                    'opens' => $from,
                    'closes' => $to,
                ];
            }
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => config('restaurant.name'),
            'url' => route('home'),
            'image' => $logoUrl,
            'telephone' => config('restaurant.phone.link'),
            'servesCuisine' => 'Chinese',
            'hasMenu' => route('home').'#menu',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => config('restaurant.address.street'),
                'postalCode' => config('restaurant.address.postal_code'),
                'addressLocality' => config('restaurant.address.city'),
                'addressCountry' => 'DE',
            ],
            'openingHoursSpecification' => $hours,
        ];
    }
}
