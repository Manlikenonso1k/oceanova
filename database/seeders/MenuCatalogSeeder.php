<?php

namespace Database\Seeders;

use App\Models\Meal;
use App\Models\MenuSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'title' => 'Full English Breakfast',
                'items' => [
                    ['name' => 'English Breakfast', 'price' => 15000, 'description' => 'Choice of eggs (fried, scrambled, or omelette) with sausage, bacon, grilled tomato, baked beans, served with tea or coffee.', 'tags' => ['P']],
                    ['name' => 'Designed Oatmeal', 'price' => 13000, 'description' => 'Creamy oatmeal with hot milk, brown sugar, and seasonal fruits.', 'tags' => ['V', 'L']],
                    ['name' => 'French Traditional Potato Omelette', 'price' => 8900, 'description' => 'Potato, bacon lardons, and herbs folded into a classic omelette; served with toast.', 'tags' => ['P']],
                ],
            ],
            [
                'title' => 'Nigerian Breakfast',
                'items' => [
                    ['name' => 'Boiled Yam with Egg Sauce or Fish Sauce', 'price' => 8000, 'description' => 'Tender boiled yam served with your choice of egg or fish sauce.'],
                    ['name' => 'Pap & Moi Moi', 'price' => 8000, 'description' => 'Pap and steamed bean pudding with milk; optional with fish or egg.'],
                    ['name' => 'Pap & Akara', 'price' => 5000, 'description' => 'Tender boiled yam served with your choice of egg or fish sauce (Titus).'],
                    ['name' => 'Pap & Okpa', 'price' => 6500, 'description' => 'Warm corn pap served with freshly fried bean cakes.'],
                    ['name' => 'Boiled Plantain with Egg Sauce or Fish Sauce', 'price' => 8000, 'description' => 'Tender boiled plantain served with your choice of egg or fish sauce.'],
                    ['name' => 'Ewa Agoyin with Bread or Plantain', 'price' => 8000, 'description' => 'Cooked beans with fried fish or fried beef (optional), tomatoes, and spicy sauce.'],
                ],
            ],
            [
                'title' => 'Chinese Breakfast',
                'items' => [
                    ['name' => 'Congee (粥)', 'price' => 12000.74, 'description' => 'Savory rice porridge served with pickles, salted eggs, or pork.'],
                    ['name' => 'Outiao & Soy Milk (油条 & ⾖浆 – Dòujiāng)', 'price' => 10000.74, 'description' => 'Deep-fried dough sticks dipped into hot or cold sweet or savory soy milk.'],
                    ['name' => 'Spicy Noodles – Zhōu (Regional Morning Noodles)', 'price' => 5300.74, 'description' => 'Regional morning noodles such as Dan Dan noodles or hot dry noodles.'],
                ],
            ],
            [
                'title' => 'Pancakes & Waffles',
                'items' => [
                    ['name' => 'Blueberry Pancakes', 'price' => 11000, 'tags' => ['L']],
                    ['name' => 'Chocolate Pancakes', 'price' => 9500],
                    ['name' => 'Plain Pancakes', 'price' => 10000],
                    ['name' => 'Chicken & Waffles', 'price' => 14000, 'description' => 'Milk-fried chicken on golden waffles.', 'tags' => ['L']],
                ],
            ],
            [
                'title' => 'Healthy Breakfast Options',
                'items' => [
                    ['name' => 'Rich Healthy Mixed Fruits & Yogurt', 'price' => 12000, 'description' => 'Seasonal fruits with creamy yogurt.', 'tags' => ['L']],
                ],
            ],
            [
                'title' => 'Continental Soups',
                'items' => [
                    ['name' => 'Laksa Soup', 'price' => 25000, 'description' => 'Spicy coconut-based noodle soup.'],
                    ['name' => 'Cream of Mushroom', 'price' => 21000, 'tags' => ['L']],
                    ['name' => 'Tom Yum', 'price' => 20000],
                    ['name' => 'Potato Soup', 'price' => 19000],
                    ['name' => 'Chicken Noodle Soup', 'price' => 17000],
                    ['name' => 'Chicken & Sweetcorn Velouté', 'price' => 14000],
                ],
            ],
            [
                'title' => 'National Soups',
                'items' => [
                    ['name' => 'Chicken Pepper Soup', 'price' => 15000, 'description' => 'Aromatic, pepper-flavoured chicken broth with yam.'],
                    ['name' => 'Cow Tail Pepper Soup', 'price' => 15000, 'description' => 'Peppery cow tail broth with fragrant local spices.'],
                    ['name' => 'Assorted Pepper Soup', 'price' => 37177, 'description' => 'Peppery assorted meat broth with fragrant local spices.'],
                    ['name' => 'Fish Pepper Soup', 'price' => 15000, 'description' => 'Light, balanced set inspired by traditional Japanese flavors and presentation.'],
                    ['name' => 'Goat Meat Pepper Soup', 'price' => 15000, 'description' => 'Peppery goat broth with fragrant local spices.'],
                    ['name' => 'Prawn Pepper Soup', 'price' => 15000, 'description' => 'Mixed seafood peppery prawn broth with calamari and fragrant local spices.', 'tags' => ['S']],
                    ['name' => 'Beef Pepper Soup', 'price' => 11000, 'description' => 'Peppery beef broth with fragrant local spices.'],
                ],
            ],
            [
                'title' => 'Salads & Add-Ons',
                'items' => [
                    ['name' => 'Coup Salad', 'price' => 15000, 'description' => 'Refreshing mix of crisp lettuce, fresh tomatoes, cucumbers, carrots, and seasonal vegetables, tossed in a light house dressing.', 'tags' => ['V']],
                    ['name' => 'Vegetable Salad', 'price' => 13000, 'description' => 'Refreshing mix of crisp lettuce, fresh tomatoes, cucumbers, carrots, and seasonal vegetables, tossed in a light house dressing.', 'tags' => ['V']],
                    ['name' => 'Oceanova Special Seafood Salad', 'price' => 12000, 'description' => 'Chef’s signature salad made with seasonal ingredients and house dressing.', 'tags' => ['S']],
                    ['name' => 'Classic Greek Salad', 'price' => 10000, 'description' => 'Cucumber, tomatoes, onions, olives, lettuce, feta cheese, and herb vinaigrette.', 'tags' => ['V', 'L']],
                    ['name' => 'Classic Tuna Caesar Salad', 'price' => 13000, 'description' => 'Crisp lettuce, tuna, poached egg, Parmesan, croutons, and Caesar dressing.'],
                    ['name' => 'Add Chicken', 'price' => 12000],
                    ['name' => 'Add Prawns', 'price' => 8500, 'tags' => ['S']],
                ],
            ],
            [
                'title' => 'Rice Dishes',
                'items' => [
                    ['name' => 'Biryani Rice', 'price' => 25000, 'description' => 'Slow-cooked basmati infused with chicken, yogurt, and traditional aromatic spices; served with sour sauce.'],
                    ['name' => 'Mongolian Rice', 'price' => 18000, 'description' => 'Flavorful fried rice tossed with vegetables in Mongolian-style sauce.'],
                    ['name' => 'Coconut or Native Rice', 'price' => 16000, 'description' => 'House signature rice with the chef’s secret blend of spices.'],
                    ['name' => 'Oceanova Special Jollof Rice', 'price' => 16000, 'description' => 'House signature rice with the chef’s secret blend of spices.'],
                    ['name' => 'Oceanova Special Fried Rice', 'price' => 16000, 'description' => 'Stir-fried rice with mixed seafood and garden vegetables.'],
                    ['name' => 'Chinese Fried Rice', 'price' => 16000, 'description' => 'Classic wok-fried rice with vegetables and light soy seasoning.'],
                    ['name' => 'Seafood Fried Rice', 'price' => 23000, 'description' => 'Stir-fried rice with assorted seafood and garden vegetables.', 'tags' => ['S']],
                ],
            ],
            [
                'title' => 'Pasta & Noodles',
                'items' => [
                    ['name' => 'Chicken Alfredo', 'price' => 25000, 'description' => 'Grilled chicken with a creamy Alfredo sauce and Parmesan.', 'tags' => ['L']],
                    ['name' => 'Shrimp Fettuccine in Tomato Sauce', 'price' => 24000, 'description' => 'Fettuccine with slow-cooked tomato sauce and succulent shrimp.', 'tags' => ['S', 'L']],
                    ['name' => 'Pasta Carbonara', 'price' => 23000, 'description' => 'Classic spaghetti with bacon, eggs, and Parmesan cream.', 'tags' => ['P', 'L']],
                    ['name' => 'Penne Pesto', 'price' => 21000, 'description' => 'Penne in a creamy basil pesto finished with Parmesan.', 'tags' => ['L', 'V']],
                    ['name' => 'Bolognese Pasta', 'price' => 21000, 'description' => 'Slow-cooked minced meat served over pasta with grated Parmesan.'],
                    ['name' => 'Stir-Fried Singaporean Noodles', 'price' => 25000, 'description' => 'Wok-tossed noodles with chicken, shrimp, and crisp vegetables in soy–oyster seasoning.'],
                    ['name' => 'Seafood Lasagna', 'price' => 23000, 'description' => 'Layered lasagna with mixed seafood, béchamel, and tomato ragu.', 'tags' => ['S', 'L']],
                ],
            ],
            [
                'title' => 'Main Courses',
                'subtitle' => 'Grill',
                'items' => [
                    ['name' => 'English Fish & Chips', 'price' => 25000, 'description' => 'Crispy battered fish served with French fries and tartar sauce.'],
                    ['name' => 'Citrus Grilled Salmon Fillet', 'price' => 25000, 'description' => 'Salmon fillet marinated in citrus herbs and grilled.', 'tags' => ['S']],
                    ['name' => 'Jumbo Grilled Prawns', 'price' => 30000, 'description' => 'Herb-marinated jumbo prawns finished with lemon butter.', 'tags' => ['S']],
                    ['name' => 'Spicy Thai Shrimps', 'price' => 25000, 'description' => 'Grilled shrimp in a spicy Thai glaze with fresh herbs.', 'tags' => ['S']],
                    ['name' => 'Marinated Grilled Fish', 'price' => 21000, 'description' => 'Fresh fish fillet marinated in house spices and flame-grilled.'],
                    ['name' => 'Grill Lobster', 'price' => 25000, 'description' => 'Premium lobster grilled to perfection with herbs and butter.', 'tags' => ['S']],
                    ['name' => 'Porterhouse T-Bone Steak', 'price' => 30000, 'description' => 'Premium porterhouse steak grilled to your preference.'],
                    ['name' => 'Turkish Grilled Chicken Kebab', 'price' => 25000, 'description' => 'Spiced chicken skewers served with flatbread and yogurt sauce.', 'tags' => ['L']],
                    ['name' => 'Buttermilk Honey-Glazed Grilled Turkey', 'price' => 25000, 'description' => 'Tender turkey marinated in buttermilk, grilled and glazed with honey butter.', 'tags' => ['L']],
                ],
            ],
            [
                'title' => 'National Dishes',
                'items' => [
                    ['name' => 'Egusi Soup', 'price' => 14000, 'description' => 'Melon-seed based soup, hearty and well-seasoned.'],
                    ['name' => 'Banga Soup', 'price' => 17000, 'description' => 'Rich palm-fruit-based soup with choice of protein.'],
                    ['name' => 'Ofe Nsala Soup', 'price' => 16000, 'description' => 'Traditional regional white soup prepared to chef’s specification; served with fish.'],
                    ['name' => 'Fisherman Soup', 'price' => 16000, 'description' => 'Hearty fish stew with market-fresh catches.'],
                    ['name' => 'Edi Kai Kong Soup', 'price' => 17000, 'description' => 'Specialty soup prepared to chef’s specification.'],
                    ['name' => 'Ogbono Soup', 'price' => 17000, 'description' => 'Thick, comforting ogbono soup served with swallow or rice.'],
                    ['name' => 'Seafood Okro Soup', 'price' => 25000, 'description' => 'Okro soup prepared with assorted seafood.'],
                ],
            ],
            [
                'title' => 'Shared Platters & Sides',
                'items' => [
                    ['name' => 'Seafood Party Platter', 'price' => 30000, 'description' => 'Mixed seafood with fries, sauces, and grilled vegetables — ideal for celebrations.', 'tags' => ['S']],
                    ['name' => 'Oceanova Seafood Platter', 'price' => 25000, 'description' => 'Generous selection of grilled fish, prawns, shrimp, calamari, and sides.', 'tags' => ['S']],
                    ['name' => 'Small Chops Platter', 'price' => 30000, 'description' => 'Spring rolls, samosas, puff-puff, and chicken wings with dipping sauces.'],
                    ['name' => 'Big House Wings', 'price' => 25000, 'description' => 'Crispy chicken wings tossed in Oceanova’s signature sauce, served with fries and dips.'],
                    ['name' => 'Vegetarian Platter', 'price' => 21000, 'description' => 'Grilled seasonal vegetables, plant-based proteins, and wholesome sides.', 'tags' => ['V']],
                    ['name' => 'Coastal Grill Steak Platter', 'price' => 25000, 'description' => 'Assorted grilled beef cuts with fries, vegetables, and sauces.'],
                    ['name' => 'South-South Platter', 'price' => 21000, 'description' => 'A Niger Delta tasting: seafood, traditional sides, and regional sauces.', 'tags' => ['S']],
                ],
            ],
            [
                'title' => 'Sides & Extras',
                'items' => [
                    ['name' => 'Plantain Fries', 'price' => 7000, 'description' => 'Sweet plantain, fried to caramelized edges.'],
                    ['name' => 'Sweet Potato Fries', 'price' => 7000, 'description' => 'Crisp sweet potato fries.'],
                    ['name' => 'Yam Fries', 'price' => 5000, 'description' => 'Golden fried yam sticks.'],
                    ['name' => 'French Fries', 'price' => 5000, 'description' => 'Crisp, twice-cooked fries.'],
                    ['name' => 'Mashed Potatoes', 'price' => 5000, 'description' => 'Creamy, hand-mashed with butter.', 'tags' => ['L']],
                    ['name' => 'Coleslaw', 'price' => 6000, 'description' => 'Shredded cabbage with a light creamy dressing.', 'tags' => ['L']],
                    ['name' => 'Oceanova Special Jollof Rice', 'price' => 7000, 'description' => 'Chef’s signature jollof with aromatic spices.'],
                    ['name' => 'Prawns', 'price' => 7000, 'description' => 'Additional portion of prawns.', 'tags' => ['S']],
                    ['name' => 'Chicken', 'price' => 15000, 'description' => 'Additional portion of grilled chicken.'],
                    ['name' => 'Shrimp', 'price' => 18500, 'description' => 'Additional portion of shrimp.', 'tags' => ['S']],
                ],
            ],
            [
                'title' => 'Red Wine',
                'items' => [
                    ['name' => 'Declan', 'price' => 20000, 'description' => 'Smooth and easy-drinking red wine.'],
                    ['name' => 'Four Cousins (Dry)', 'price' => 20000, 'description' => 'Medium-bodied dry red with soft tannins and fruity notes.'],
                    ['name' => 'Carlo Rossi', 'price' => 25000, 'description' => 'Well-balanced red wine with rich berry flavors.'],
                    ['name' => 'Apothic', 'price' => 27000, 'description' => 'Bold red blend with hints of dark fruit and vanilla.'],
                    ['name' => '4th Street', 'price' => 18000, 'description' => 'Light-bodied red wine with a smooth finish.'],
                    ['name' => 'Asconi Agor', 'price' => 27000, 'description' => 'Structured red wine with balanced acidity and fruit tones.'],
                    ['name' => 'Massimo (Merlot / Cabernet Sauvignon)', 'price' => 45000, 'description' => 'Premium red with rich character and layered flavors.'],
                    ['name' => 'Escudo Rojo', 'price' => 40000, 'description' => 'Full-bodied Chilean red with intense fruit and oak notes.'],
                    ['name' => 'Nederburg (Cabernet Sauvignon)', 'price' => 35000, 'description' => 'Classic Cabernet Sauvignon with deep berry and spice notes.'],
                ],
            ],
            [
                'title' => 'White Wine',
                'items' => [
                    ['name' => 'Four Cousins (Dry)', 'price' => 18000, 'description' => 'Fresh and fruity dry white wine with a smooth finish.'],
                    ['name' => '4th Street', 'price' => 20000, 'description' => 'Light-bodied white wine with crisp fruit notes.'],
                    ['name' => 'Castillo Grande', 'price' => 27000, 'description' => 'Well-balanced white wine with soft fruit aromas and a clean finish.'],
                    ['name' => 'Nederburg (Sauvignon Blanc)', 'price' => 35000, 'description' => 'Vibrant Sauvignon Blanc with citrus and tropical flavors.'],
                    ['name' => 'Massimo', 'price' => 40000, 'description' => 'Premium structured wine with refined character and smooth body.'],
                    ['name' => 'Escudo Rojo', 'price' => 45000, 'description' => 'Elegant, full-flavored wine with rich fruit expression.'],
                    ['name' => 'Nederburg', 'price' => 35000, 'description' => 'Classic, well-balanced wine with fresh acidity and layered fruit notes.'],
                    ['name' => 'Clarendelle Bordeaux', 'price' => 50000, 'description' => 'Elegant Bordeaux blend with refined acidity and balanced fruit character.'],
                ],
            ],
            [
                'title' => 'Liqueur',
                'items' => [
                    ['name' => 'Baileys Irish Cream', 'price' => 35000, 'description' => 'Creamy liqueur combining Irish whiskey and chocolate flavors.'],
                    ['name' => 'Jägermeister', 'price' => 30000, 'description' => 'Herbal liqueur with bold spices and a smooth finish.'],
                    ['name' => 'Ivory Cream', 'price' => 27000, 'description' => 'Sweet cream-based liqueur with a rich, smooth texture.'],
                ],
            ],
            [
                'title' => 'Tequila',
                'items' => [
                    ['name' => 'Olmeca Tequila', 'price' => 45000, 'description' => 'Smooth agave flavor with a lively and slightly peppery finish.'],
                    ['name' => 'Sierra Tequila', 'price' => 37000, 'description' => 'Fresh and vibrant tequila with light fruity notes.'],
                ],
            ],
            [
                'title' => 'Cognac',
                'items' => [
                    ['name' => 'Martell VS', 'price' => 75000, 'description' => 'A youthful cognac with fruity notes and a smooth oak finish.'],
                    ['name' => 'Martell Blue Swift', 'price' => 140000, 'description' => 'Modern cognac finished in bourbon casks with hints of vanilla and spice.'],
                    ['name' => 'Hennessy VS', 'price' => 90000, 'description' => 'Bold and vibrant cognac with toasted oak and fruit flavors.'],
                    ['name' => 'Hennessy VSOP', 'price' => 150000, 'description' => 'Mature, balanced cognac offering smooth spice and rich character.'],
                ],
            ],
            [
                'title' => 'Vodka',
                'items' => [
                    ['name' => 'Sky Vodka', 'price' => 27000, 'description' => 'Smooth and light with a clean finish, perfect for mixed drinks.'],
                    ['name' => 'Absolut Vodka', 'price' => 30000, 'description' => 'Premium Swedish vodka known for purity and balanced flavor.'],
                    ['name' => 'Flirt Vodka', 'price' => 17000, 'description' => 'Easy-drinking vodka with a soft, neutral profile.'],
                ],
            ],
            [
                'title' => 'Gin',
                'items' => [
                    ['name' => 'Gordon’s Gin', 'price' => 30000, 'description' => 'Classic London dry gin with juniper-forward notes.'],
                    ['name' => 'Bombay Sapphire', 'price' => 40000, 'description' => 'Smooth premium gin with floral and citrus botanicals.'],
                ],
            ],
            [
                'title' => 'Whiskey',
                'items' => [
                    ['name' => 'Glenfiddich (12 Years)', 'price' => 90000, 'description' => 'Single malt Scotch with pear, oak, and subtle sweetness.'],
                    ['name' => 'Jameson', 'price' => 45000, 'description' => 'Smooth Irish whiskey with vanilla and toasted wood notes.'],
                    ['name' => 'Jameson Black Barrel', 'price' => 70000, 'description' => 'Rich and intense whiskey with deeper spice and caramel tones.'],
                    ['name' => 'Jack Daniel’s', 'price' => 50000, 'description' => 'Classic Tennessee whiskey with sweet oak and smoky finish.'],
                ],
            ],
            [
                'title' => 'Cocktails',
                'items' => [
                    ['name' => 'Margarita', 'price' => 12900, 'description' => 'Tequila, Triple Sec, Lemon Juice, Simple Syrup'],
                    ['name' => 'Blue Lagoon', 'price' => 10750, 'description' => 'Vodka, Blue Curacao, Lime Juice, Simple Syrup'],
                    ['name' => 'Mojito', 'price' => 10750, 'description' => 'Rum, Sugar, Mint, Lime, Soda Water'],
                    ['name' => 'Daiquiri', 'price' => 10750, 'description' => 'Rum, Lemon Juice, Simple Syrup'],
                    ['name' => 'Cosmopolitan', 'price' => 10750, 'description' => 'Vodka, Triple Sec, Cranberry Juice, Lemon Juice'],
                    ['name' => 'Tequila Sunrise', 'price' => 10750, 'description' => 'Tequila, Orange Juice, Grenadine'],
                    ['name' => 'Long Island Iced Tea', 'price' => 10750, 'description' => 'Gin, Rum, Vodka, Tequila, Triple Sec, Lime Juice, Coke'],
                    ['name' => 'Martini', 'price' => 10750, 'description' => 'Gin or Vodka (classic preparation)'],
                    ['name' => 'Sex on the Beach', 'price' => 10750, 'description' => 'Vodka, Cranberry Juice, Orange Juice, Peach Schnapps'],
                    ['name' => 'Piña Colada', 'price' => 10750, 'description' => 'Rum, Coconut Cream, Pineapple Juice'],
                    ['name' => 'Whiskey Sour', 'price' => 10750, 'description' => 'Whiskey, Lemon Juice, Simple Syrup, Egg White'],
                ],
            ],
            [
                'title' => 'Mocktails',
                'items' => [
                    ['name' => 'Shirley Temple', 'price' => 6450, 'description' => 'Grenadine, Lemon Juice, Sprite'],
                    ['name' => 'Virgin Bellini', 'price' => 8600, 'description' => 'Flavoured Syrup, Lemon Syrup, Soda Water'],
                    ['name' => 'Rainbow Paradise', 'price' => 8600, 'description' => 'Grenadine, Orange Juice, Orange Soda, Citrus Soda, Bitters'],
                    ['name' => 'Blue Ocean', 'price' => 7525, 'description' => 'Blue Curacao, Lemon Juice, Simple Syrup, Sprite'],
                    ['name' => 'Virgin Mojito', 'price' => 5300.74, 'description' => 'Mint, Sugar, Lime, Soda'],
                    ['name' => 'Iced Tea', 'price' => 8600, 'description' => 'Simple Syrup, Lemon Juice, Tea Bag'],
                    ['name' => 'Chapman', 'price' => 6450, 'description' => 'Grenadine, Lemon Juice, Citrus Soda'],
                    ['name' => 'Apple Cooler', 'price' => 8600, 'description' => 'Apple Juice, Honey, Lemon Juice, Sprite'],
                    ['name' => 'Citrus-Ginger Fritz', 'price' => 8600, 'description' => 'Ginger Juice, Lemon Syrup, Honey, Sprite'],
                    ['name' => 'Virgin Colada', 'price' => 5300.74, 'description' => 'Coconut Blend, Pineapple Juice, Cream'],
                    ['name' => 'Lemonade', 'price' => 7525, 'description' => 'Lemon Juice, Simple Syrup, Soda'],
                ],
            ],
        ];

        foreach ($sections as $sectionIndex => $sectionData) {
            $section = MenuSection::query()->updateOrCreate(
                ['slug' => Str::slug($sectionData['title'])],
                [
                    'title' => $sectionData['title'],
                    'subtitle' => $sectionData['subtitle'] ?? null,
                    'sort_order' => $sectionIndex + 1,
                    'is_active' => true,
                ]
            );

            foreach ($sectionData['items'] as $itemIndex => $item) {
                $baseSlug = Str::slug($sectionData['title'].'-'.$item['name']);
                $slug = $baseSlug;
                $counter = 2;

                while (
                    Meal::query()
                        ->where('slug', $slug)
                        ->where('menu_section_id', '!=', $section->id)
                        ->exists()
                ) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }

                Meal::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'menu_section_id' => $section->id,
                        'name' => $item['name'],
                        'slug' => $slug,
                        'price' => $item['price'],
                        'category' => $sectionData['title'],
                        'description' => $item['description'] ?? null,
                        'image' => $item['image'] ?? null,
                        'tags' => $item['tags'] ?? [],
                        'sort_order' => $itemIndex + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
