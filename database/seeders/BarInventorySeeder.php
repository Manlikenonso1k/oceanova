<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class BarInventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Martell Blue Swift', 'sub_category' => 'Cognac', 'price' => 210000],
            ['name' => 'Hennessy VSOP', 'sub_category' => 'Cognac', 'price' => 190000],
            ['name' => 'Hennessy VS', 'sub_category' => 'Cognac', 'price' => 120000],
            ['name' => 'Martell VS', 'sub_category' => 'Cognac', 'price' => 115000],

            ['name' => 'Glenfiddich (12yrs)', 'sub_category' => 'Whiskey', 'price' => 165000],
            ['name' => 'Jack Williams', 'sub_category' => 'Whiskey', 'price' => 95000],
            ['name' => 'Red Label', 'sub_category' => 'Whiskey', 'price' => 60000],
            ['name' => 'Jameson Black Barrel', 'sub_category' => 'Whiskey', 'price' => 145000],
            ['name' => 'Jack Daniels', 'sub_category' => 'Whiskey', 'price' => 98000],
            ['name' => 'Jameson', 'sub_category' => 'Whiskey', 'price' => 78000],

            ['name' => 'Massimo (Merlot/Cab.)', 'sub_category' => 'Red Wine', 'price' => 42000],
            ['name' => 'Richman Baron', 'sub_category' => 'Red Wine', 'price' => 35000],
            ['name' => 'Cosaco', 'sub_category' => 'Red Wine', 'price' => 26000],
            ['name' => 'Nuggan (Cabsauv)', 'sub_category' => 'Red Wine', 'price' => 38000],
            ['name' => 'Baron Romero', 'sub_category' => 'Red Wine', 'price' => 24000],
            ['name' => 'Drevni Red', 'sub_category' => 'Red Wine', 'price' => 25000],
            ['name' => 'Grand Moscato', 'sub_category' => 'Red Wine', 'price' => 30000],
            ['name' => 'Four Cousins', 'sub_category' => 'Red Wine', 'price' => 22000],
            ['name' => 'Escudo Rojo', 'sub_category' => 'Red Wine', 'price' => 70000],
            ['name' => 'Gran Mirador', 'sub_category' => 'Red Wine', 'price' => 28000],
            ['name' => 'Nederburg (Cab. Sauv)', 'sub_category' => 'Red Wine', 'price' => 43000],
            ['name' => 'Apothic', 'sub_category' => 'Red Wine', 'price' => 52000],
            ['name' => 'Asconi Agor (Red Rose)', 'sub_category' => 'Red Wine', 'price' => 26000],
            ['name' => 'Carlo Rossi (Red)', 'sub_category' => 'Red Wine', 'price' => 34000],
            ['name' => 'Declan', 'sub_category' => 'Red Wine', 'price' => 21000],
            ['name' => 'Four Cousins (Dry Red)', 'sub_category' => 'Red Wine', 'price' => 23000],
            ['name' => '4th Street (Red)', 'sub_category' => 'Red Wine', 'price' => 17000],

            ['name' => 'Clarendelle Bordeaux', 'sub_category' => 'White Wine', 'price' => 65000],
            ['name' => 'Nederburg (Sauv. Blanc)', 'sub_category' => 'White Wine', 'price' => 39000],
            ['name' => 'Castillo Grande', 'sub_category' => 'White Wine', 'price' => 24000],

            ['name' => 'Four Cousins Sweet (Red)', 'sub_category' => 'Sweet Wine Red', 'price' => 22000],
            ['name' => 'Four Cousins Rose', 'sub_category' => 'Sweet Wine Red', 'price' => 22000],
            ['name' => 'Four Cousins (Dry Wht)', 'sub_category' => 'Sweet Wine Red', 'price' => 22500],
            ['name' => 'Cosaco Sweet Red', 'sub_category' => 'Sweet Wine Red', 'price' => 23000],
            ['name' => 'Asconi Agor (Sweet)', 'sub_category' => 'Sweet Wine Red', 'price' => 25000],
            ['name' => 'Carlo Rossi (Sweet)', 'sub_category' => 'Sweet Wine Red', 'price' => 34000],
            ['name' => 'Four Cousins (Sweet Rose)', 'sub_category' => 'Sweet Wine Red', 'price' => 22500],
            ['name' => '4th Street (Sweet Red)', 'sub_category' => 'Sweet Wine Red', 'price' => 17000],

            ['name' => 'Olnomelo', 'sub_category' => 'Sweet Wine White', 'price' => 18000],
            ['name' => '4th Street (White)', 'sub_category' => 'Sweet Wine White', 'price' => 17000],
            ['name' => 'Four Cousins Sweet (Wht)', 'sub_category' => 'Sweet Wine White', 'price' => 22000],
            ['name' => 'Four Cousins Rose (White)', 'sub_category' => 'Sweet Wine White', 'price' => 22000],

            ['name' => 'Olmeca', 'sub_category' => 'Tequila', 'price' => 90000],
            ['name' => 'Sierra', 'sub_category' => 'Tequila', 'price' => 85000],

            ['name' => 'Bombay Sapphire', 'sub_category' => 'Vodka/Gin', 'price' => 70000],
            ['name' => 'Absolut Vodka', 'sub_category' => 'Vodka/Gin', 'price' => 65000],
            ['name' => "Gordon's Gin", 'sub_category' => 'Vodka/Gin', 'price' => 50000],
            ['name' => 'Sky Vodka', 'sub_category' => 'Vodka/Gin', 'price' => 42000],
            ['name' => 'Flirt Vodka', 'sub_category' => 'Vodka/Gin', 'price' => 38000],

            ['name' => 'Baileys', 'sub_category' => 'Liqueur', 'price' => 70000],
            ['name' => 'Jägermeister', 'sub_category' => 'Liqueur', 'price' => 72000],
            ['name' => 'Ivory Cream', 'sub_category' => 'Liqueur', 'price' => 35000],
            ['name' => 'Krupnikas', 'sub_category' => 'Liqueur', 'price' => 30000],

            ['name' => 'Pink Lady', 'sub_category' => 'Spark/Soft', 'price' => 15000],
            ['name' => 'Andre', 'sub_category' => 'Spark/Soft', 'price' => 16000],
            ['name' => 'Saint Lauren', 'sub_category' => 'Spark/Soft', 'price' => 18000],
            ['name' => "Welch's", 'sub_category' => 'Spark/Soft', 'price' => 14000],
            ['name' => 'Chamdor', 'sub_category' => 'Spark/Soft', 'price' => 12000],
            ['name' => 'Eva Gold', 'sub_category' => 'Spark/Soft', 'price' => 13000],
            ['name' => 'Casa Dorada', 'sub_category' => 'Spark/Soft', 'price' => 19000],
        ];

        foreach ($items as $item) {
            Ingredient::query()->updateOrCreate(
                ['name' => $item['name']],
                [
                    'category' => 'Beverage',
                    'sub_category' => $item['sub_category'],
                    'unit' => 'pcs',
                    'price' => $item['price'],
                    'current_stock' => 0,
                    'min_stock_alert_level' => 5,
                ]
            );
        }
    }
}
