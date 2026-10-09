<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Initial menu, typed from the photos in resources/images/speisekarte/.
 * Do not add anything that is not printed on the menu.
 * Codes that are hard to read on the photos are listed in docs/OPEN_QUESTIONS.md (question 17).
 *
 * German name = large print, German description = small print on the menu.
 * English texts are translations. Obvious misprints are normalised
 * ("Chop-Seuy" → "Chop-Suey", "Karton-Art" → "Kanton-Art", "Broccolie" → "Brokkoli").
 */
class MenuSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only the initial fill: afterwards the owner maintains the menu in the admin area.
        if (MenuCategory::query()->exists()) {
            return;
        }

        foreach ($this->menu() as $categoryIndex => [$nameDe, $nameEn, $items]) {
            $category = MenuCategory::create([
                'name_de' => $nameDe,
                'name_en' => $nameEn,
                'sort_order' => ($categoryIndex + 1) * 10,
            ]);

            foreach ($items as $itemIndex => [$number, $itemDe, $descriptionDe, $itemEn, $descriptionEn, $priceCents, $allergens, $isSpicy]) {
                $category->items()->create([
                    'number' => $number === null ? null : (string) $number,
                    'name_de' => $itemDe,
                    'name_en' => $itemEn,
                    'description_de' => $descriptionDe,
                    'description_en' => $descriptionEn,
                    'price_cents' => $priceCents,
                    'is_spicy' => $isSpicy,
                    'allergens' => $allergens ?: null,
                    'sort_order' => ($itemIndex + 1) * 10,
                ]);
            }
        }
    }

    /**
     * Categories as [name_de, name_en, items]; items as
     * [number, name_de, description_de, name_en, description_en, price_cents, allergens, is_spicy].
     *
     * @return list<array{string, string, list<array{int|string|null, string, ?string, string, ?string, int, list<string>, bool}>}>
     */
    private function menu(): array
    {
        return [
            ['Vorspeisen', 'Starters', [
                [1, 'Pekingsuppe', null, 'Peking soup', null, 300, ['1', '7', 'A'], true],
                [2, 'Krabbenchips', null, 'Prawn crackers', null, 200, ['5', 'A', 'B'], false],
                [3, 'Nudelsuppe', 'mit Hühnerfleisch & Gemüse', 'Noodle soup', 'with chicken & vegetables', 400, ['7', 'A'], false],
                [4, 'Vegetarische Frühlingsrollen', '6 Stück mit süß-sauer Soße', 'Vegetarian spring rolls', '6 pieces with sweet and sour sauce', 300, ['7', 'A', 'F', 'N', 'H'], false],
                [5, 'Hühnerfleisch in Teig frittiert', '8 Stück mit süß-sauer Soße', 'Chicken fried in batter', '8 pieces with sweet and sour sauce', 500, ['7', 'A'], false],
            ]],

            ['Gebratene Nudeln', 'Fried noodles', [
                [6, 'mit Hühnerfleisch', 'Ei & Gemüse', 'with chicken', 'egg & vegetables', 1100, ['7', 'C'], false],
                [7, 'mit Schweinefleisch', 'Ei & Gemüse', 'with pork', 'egg & vegetables', 1100, ['7', 'C'], false],
                [8, 'mit Rindfleisch', 'Ei & Gemüse', 'with beef', 'egg & vegetables', 1200, ['7', 'C'], false],
                [9, 'mit Garnelen', 'Ei & Gemüse', 'with prawns', 'egg & vegetables', 1200, ['7', 'B', 'C'], false],
                [10, 'mit Entenfleisch', 'Ei & Gemüse', 'with duck', 'egg & vegetables', 1200, ['7', 'C'], false],
                [11, 'Verschiedene Fleischsorten', 'mit Ei & Gemüse', 'Mixed meats', 'with egg & vegetables', 1200, ['7', 'B', 'C'], false],
            ]],

            ['Gebratener Reis', 'Fried rice', [
                [12, 'mit Hühnerfleisch', 'Ei & Gemüse', 'with chicken', 'egg & vegetables', 1100, ['7', 'C'], false],
                [13, 'mit Schweinefleisch', 'Ei & Gemüse', 'with pork', 'egg & vegetables', 1100, ['7', 'C'], false],
                [14, 'mit Rindfleisch', 'Ei & Gemüse', 'with beef', 'egg & vegetables', 1200, ['7', 'C'], false],
                [15, 'mit Garnelen', 'Ei & Gemüse', 'with prawns', 'egg & vegetables', 1200, ['7', 'B', 'C'], false],
                [16, 'mit Entenfleisch', 'Ei & Gemüse', 'with duck', 'egg & vegetables', 1200, ['7', 'C'], false],
                [17, 'Verschiedene Fleischsorten', 'mit Ei & Gemüse', 'Mixed meats', 'with egg & vegetables', 1200, ['7', 'B', 'C'], false],
            ]],

            ['Hühnerfleisch', 'Chicken', [
                [18, 'Chop-Suey', 'mit Gemüse', 'Chop suey', 'with vegetables', 1100, ['7', 'A'], false],
                [19, 'mit Sojabohnen & roter Paprika', null, 'with soybeans & red pepper', null, 1100, ['7', 'A', 'F'], false],
                [20, 'mit Brokkoli & roter Paprika', null, 'with broccoli & red pepper', null, 1100, ['7', 'A'], true],
                [21, 'Süß-Sauer-Scharf', 'mit Gemüse', 'Sweet, sour and spicy', 'with vegetables', 1100, ['A'], true],
                [22, 'Szechuan-Art', 'mit Gemüse', 'Szechuan style', 'with vegetables', 1100, ['7', 'A'], true],
                [23, 'Koon-Poo', 'mit Gemüse & Knoblauch', 'Koon-Poo', 'with vegetables & garlic', 1100, ['7', 'A'], true],
                [24, 'Thai-Curry', 'mit Gemüse', 'Thai curry', 'with vegetables', 1100, ['7', 'A', 'G'], true],
                [25, 'Erdnusssoße', 'mit Gemüse', 'Peanut sauce', 'with vegetables', 1100, ['7', 'A', 'H'], false],
                [26, 'Kanton-Art', 'mit Gemüse & Knoblauch', 'Canton style', 'with vegetables & garlic', 1100, ['7', 'A'], true],
                [27, '„Gu-Lao-Kai“', 'in Teig frittiert mit süß-sauer Soße', '“Gu-Lao-Kai”', 'fried in batter with sweet and sour sauce', 1100, ['7', 'A'], false],
                [28, '„Spezialsoße“ Hühnerbrust', 'knusprig mit Gemüse', '“Special sauce” chicken breast', 'crispy, with vegetables', 1100, ['7', 'A'], true],
                [29, 'Hühnerbrust', 'knusprig mit Gemüse', 'Chicken breast', 'crispy, with vegetables', 1100, ['7', 'A'], false],
                [30, '„Thai-Curry“ Hühnerbrust', 'knusprig mit Gemüse', '“Thai curry” chicken breast', 'crispy, with vegetables', 1100, ['7', 'A', 'G'], true],
            ]],

            ['Rindfleischgerichte', 'Beef dishes', [
                [31, 'Chop-Suey', 'mit Gemüse', 'Chop suey', 'with vegetables', 1200, ['7', 'A'], false],
                [32, 'mit Zwiebeln', 'roter Paprika & Knoblauch', 'with onions', 'red pepper & garlic', 1200, ['7', 'A'], true],
                [33, 'Szechuan-Art', 'mit Gemüse', 'Szechuan style', 'with vegetables', 1200, ['7', 'A'], true],
                [34, 'Koon-Poo', 'mit Gemüse & Knoblauch', 'Koon-Poo', 'with vegetables & garlic', 1200, ['7', 'A'], true],
                [35, 'Thai-Curry', 'mit Gemüse', 'Thai curry', 'with vegetables', 1200, ['7', 'A', 'G'], true],
                [36, 'Kanton-Art', 'mit Gemüse & Knoblauch', 'Canton style', 'with vegetables & garlic', 1200, ['7', 'A'], true],
                [37, 'mit Brokkoli', '& roter Paprika', 'with broccoli', '& red pepper', 1200, ['7', 'A'], true],
                [38, 'Süß-Sauer-Scharf', 'mit Gemüse', 'Sweet, sour and spicy', 'with vegetables', 1200, ['A'], true],
                [39, 'Erdnusssoße', 'mit Gemüse', 'Peanut sauce', 'with vegetables', 1200, ['7', 'A', 'H'], false],
                [40, '„Spezialsoße“', 'mit Gemüse', '“Special sauce”', 'with vegetables', 1200, ['7', 'A'], true],
            ]],

            ['Schweinefleischgerichte', 'Pork dishes', [
                [41, 'Chop-Suey', 'mit Gemüse', 'Chop suey', 'with vegetables', 1100, ['7', 'A'], false],
                [42, 'mit Sojabohnen & roter Paprika', null, 'with soybeans & red pepper', null, 1100, ['7', 'A', 'F'], false],
                [43, 'Süß-Sauer-Scharf', 'mit Gemüse', 'Sweet, sour and spicy', 'with vegetables', 1100, ['A'], true],
                [44, 'Szechuan-Art', 'mit Gemüse', 'Szechuan style', 'with vegetables', 1100, ['7', 'A'], true],
                [45, 'Koon-Poo', 'mit Gemüse & Knoblauch', 'Koon-Poo', 'with vegetables & garlic', 1100, ['7', 'A'], true],
                [46, 'Kanton-Art', 'mit Gemüse und Knoblauch', 'Canton style', 'with vegetables and garlic', 1100, ['7', 'A'], true],
                [47, 'Thai-Curry', 'mit Gemüse', 'Thai curry', 'with vegetables', 1100, ['7', 'A', 'G'], true],
                [48, '„Spezialsoße“', 'mit Gemüse', '“Special sauce”', 'with vegetables', 1100, ['7', 'A'], true],
            ]],

            ['Entengerichte', 'Duck dishes', [
                [49, 'Chop-Suey', 'mit Gemüse', 'Chop suey', 'with vegetables', 1200, ['7', 'A'], false],
                [50, 'mit Brokkoli', '& roter Paprika', 'with broccoli', '& red pepper', 1200, ['7', 'A'], true],
                [51, 'Kanton-Art', 'mit Gemüse & Knoblauch', 'Canton style', 'with vegetables & garlic', 1200, ['7', 'A'], true],
                [52, 'Süß-Sauer', 'mit Gemüse', 'Sweet and sour', 'with vegetables', 1200, ['A'], false],
                [53, 'Szechuan-Art', 'mit Gemüse', 'Szechuan style', 'with vegetables', 1200, ['7', 'A'], true],
                [54, 'Koon-Poo', 'mit Gemüse & Knoblauch', 'Koon-Poo', 'with vegetables & garlic', 1200, ['7', 'A'], true],
                [55, 'Erdnusssoße', 'mit Gemüse', 'Peanut sauce', 'with vegetables', 1200, ['7', 'A', 'H'], false],
                [56, 'Thai-Curry', 'mit Gemüse', 'Thai curry', 'with vegetables', 1200, ['7', 'A', 'G'], true],
                [57, '„Spezialsoße“', 'mit Gemüse', '“Special sauce”', 'with vegetables', 1200, ['7', 'A'], true],
            ]],

            ['Garnelengerichte', 'Prawn dishes', [
                [58, 'Chop-Suey', 'mit Gemüse', 'Chop suey', 'with vegetables', 1200, ['7', 'A', 'B'], false],
                [59, 'mit Brokkoli', '& roter Paprika', 'with broccoli', '& red pepper', 1200, ['7', 'A', 'B'], true],
                [60, 'Koon-Poo', 'mit Gemüse & Knoblauch', 'Koon-Poo', 'with vegetables & garlic', 1200, ['7', 'A', 'B'], true],
                [61, 'Szechuan-Art', 'mit Gemüse', 'Szechuan style', 'with vegetables', 1200, ['7', 'A', 'B'], true],
                [62, 'Kanton-Art', 'mit Gemüse & Knoblauch', 'Canton style', 'with vegetables & garlic', 1200, ['7', 'A', 'B'], true],
                [63, 'Thai-Curry', 'mit Gemüse', 'Thai curry', 'with vegetables', 1200, ['7', 'A', 'B', 'G'], true],
                [64, 'Süß-Sauer-Scharf', 'mit Gemüse', 'Sweet, sour and spicy', 'with vegetables', 1200, ['A', 'B'], true],
                [65, 'Erdnusssoße', 'mit Gemüse', 'Peanut sauce', 'with vegetables', 1200, ['7', 'A', 'B', 'H'], false],
                [66, '„Spezialsoße“', 'mit Gemüse', '“Special sauce”', 'with vegetables', 1200, ['7', 'A', 'B'], true],
            ]],

            ['Vegetarisches', 'Vegetarian', [
                [67, 'Gebratene Nudeln', 'mit Gemüse', 'Fried noodles', 'with vegetables', 900, ['7', 'A'], false],
                [68, 'Gebratener Reis', 'mit Gemüse', 'Fried rice', 'with vegetables', 900, ['7', 'A'], false],
                [69, 'Chop-Suey', 'mit Gemüse', 'Chop suey', 'with vegetables', 900, ['7', 'A'], false],
                [70, 'Chop-Kam', 'mit Gemüse', 'Chop-Kam', 'with vegetables', 900, ['7', 'A'], true],
            ]],

            ['Desserts', 'Desserts', [
                [null, 'Gebackene Banane', 'mit Honig', 'Fried banana', 'with honey', 400, ['A'], false],
                [null, 'Gebackene Ananas', 'mit Honig', 'Fried pineapple', 'with honey', 400, ['A'], false],
            ]],

            ['Extras', 'Extras', [
                [null, 'Gekochter Reis', 'pro Portion', 'Boiled rice', 'per portion', 200, [], false],
                [null, 'Gebratene Nudeln/Reis', 'anstatt gekochter Reis (Aufpreis)', 'Fried noodles/rice', 'instead of boiled rice (surcharge)', 300, ['7', 'A'], false],
                [null, 'Sambal Oelek', null, 'Sambal oelek', null, 100, ['5'], true],
                [null, 'Erdnuss- oder Süßsauersoße', 'pro Portion', 'Peanut or sweet and sour sauce', 'per portion', 200, ['H'], false],
            ]],

            // Set menus for two; allergens are the union of all three courses.
            ['Spezialitäten des Hauses', 'House specialities', [
                ['B1', 'Hong-Kong Ente', 'für 2 Personen: 1) Frühlingsrollen oder Pekingsuppe (scharf), 2) Ente knusprig mit Gemüse & „Spezialsoße“ (scharf), 3) Kaffee oder gebackene Banane/Ananas mit Honig', 'Hong Kong duck', 'for 2 people: 1) spring rolls or Peking soup (spicy), 2) crispy duck with vegetables & “special sauce” (spicy), 3) coffee or fried banana/pineapple with honey', 3400, ['1', '3', '7', 'A', 'F', 'H', 'N'], false],
                ['B2', 'Pa-Pao-Ha', 'für 2 Personen: 1) Frühlingsrollen oder Pekingsuppe (scharf), 2) Garnelen mit Gemüse & „Spezialsoße“ (scharf), 3) Kaffee oder gebackene Banane/Ananas mit Honig', 'Pa-Pao-Ha', 'for 2 people: 1) spring rolls or Peking soup (spicy), 2) prawns with vegetables & “special sauce” (spicy), 3) coffee or fried banana/pineapple with honey', 3400, ['1', '3', '7', 'A', 'B', 'F', 'H', 'N'], false],
                ['B3', 'Pa-Pao-Thai-Art', 'für 2 Personen: 1) Frühlingsrollen oder Pekingsuppe (scharf), 2) Verschiedene Fleischsorten mit Gemüse (scharf), 3) Kaffee oder gebackene Banane/Ananas mit Honig', 'Pa-Pao Thai style', 'for 2 people: 1) spring rolls or Peking soup (spicy), 2) mixed meats with vegetables (spicy), 3) coffee or fried banana/pineapple with honey', 3400, ['1', '3', '7', 'A', 'F', 'H', 'N'], false],
                ['B4', 'Pa-Pao-Shang-Hai', 'für 2 Personen: 1) Frühlingsrollen oder Pekingsuppe (scharf), 2) Garnelen & verschiedene Fleischsorten mit Gemüse & Knoblauch (scharf), 3) Kaffee oder gebackene Banane/Ananas mit Honig', 'Pa-Pao Shanghai', 'for 2 people: 1) spring rolls or Peking soup (spicy), 2) prawns & mixed meats with vegetables & garlic (spicy), 3) coffee or fried banana/pineapple with honey', 3400, ['1', '3', '7', 'A', 'B', 'F', 'H', 'N'], false],
            ]],

            ['Getränke', 'Drinks', [
                [null, 'Cola', '1 l', 'Cola', '1 l', 300, ['1', '3', '5'], false],
                [null, 'Cola light', '1 l', 'Cola light', '1 l', 300, ['1', '3', '5', '10'], false],
                [null, 'Fanta', '1 l', 'Fanta', '1 l', 300, ['1', '2', '5'], false],
                [null, 'Sprite', '1 l', 'Sprite', '1 l', 300, ['5'], false],
                [null, 'Apfelschorle', '1,5 l', 'Apple spritzer', '1.5 l', 400, ['5'], false],
                [null, 'Mineralwasser', '0,75 l', 'Mineral water', '0.75 l', 300, [], false],
                [null, 'Bitburger Pils', '0,5 l', 'Bitburger Pils', '0.5 l', 300, [], false],
                [null, 'Bitburger Alkoholfrei', '0,5 l', 'Bitburger alcohol-free', '0.5 l', 300, [], false],
                [null, 'Weizenbier', '0,5 l', 'Wheat beer', '0.5 l', 400, [], false],
            ]],
        ];
    }
}
