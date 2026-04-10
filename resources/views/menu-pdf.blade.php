@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;

    if (empty($sections ?? null)) {
    $sections = [
        [
            'title' => 'Full English Breakfast',
            'items' => [
                ['name' => 'English Breakfast', 'price' => '₦15,000', 'description' => 'Choice of eggs (fried, scrambled, or omelette) with sausage, bacon, grilled tomato, baked beans, served with tea or coffee.', 'tags' => ['P']],
                ['name' => 'Designed Oatmeal', 'price' => '₦13,000', 'description' => 'Creamy oatmeal with hot milk, brown sugar, and seasonal fruits.', 'tags' => ['V', 'L']],
                ['name' => 'French Traditional Potato Omelette', 'price' => '₦8,900', 'description' => 'Potato, bacon lardons, and herbs folded into a classic omelette; served with toast.', 'tags' => ['P']],
            ],
        ],
        [
            'title' => 'Nigerian Breakfast',
            'items' => [
                ['name' => 'Boiled Yam with Egg Sauce or Fish Sauce', 'price' => '₦8,000', 'description' => 'Tender boiled yam served with your choice of egg or fish sauce.'],
                ['name' => 'Pap & Moi Moi', 'price' => '₦8,000', 'description' => 'Pap and steamed bean pudding with milk; optional with fish or egg.'],
                ['name' => 'Pap & Akara', 'price' => '₦5,000', 'description' => 'Tender boiled yam served with your choice of egg or fish sauce (Titus).'],
                ['name' => 'Pap & Okpa', 'price' => '₦6,500', 'description' => 'Warm corn pap served with freshly fried bean cakes.'],
                ['name' => 'Boiled Plantain with Egg Sauce or Fish Sauce', 'price' => '₦8,000', 'description' => 'Tender boiled plantain served with your choice of egg or fish sauce.'],
                ['name' => 'Ewa Agoyin with Bread or Plantain', 'price' => '₦8,000', 'description' => 'Cooked beans with fried fish or fried beef (optional), tomatoes, and spicy sauce.'],
            ],
        ],
        [
            'title' => 'Chinese Breakfast',
            'items' => [
                ['name' => 'Congee (粥)', 'price' => '₦12,000.74', 'description' => 'Savory rice porridge served with pickles, salted eggs, or pork.'],
                ['name' => 'Outiao & Soy Milk (油条 & ⾖浆 – Dòujiāng)', 'price' => '₦10,000.74', 'description' => 'Deep-fried dough sticks dipped into hot or cold sweet or savory soy milk.'],
                ['name' => 'Spicy Noodles – Zhōu (Regional Morning Noodles)', 'price' => '₦5,300.74', 'description' => 'Regional morning noodles such as Dan Dan noodles or hot dry noodles.'],
            ],
        ],
        [
            'title' => 'Pancakes & Waffles',
            'items' => [
                ['name' => 'Blueberry Pancakes', 'price' => '₦11,000', 'tags' => ['L']],
                ['name' => 'Chocolate Pancakes', 'price' => '₦9,500'],
                ['name' => 'Plain Pancakes', 'price' => '₦10,000'],
                ['name' => 'Chicken & Waffles', 'price' => '₦14,000', 'description' => 'Milk-fried chicken on golden waffles.', 'tags' => ['L']],
            ],
        ],
        [
            'title' => 'Healthy Breakfast Options',
            'items' => [
                ['name' => 'Rich Healthy Mixed Fruits & Yogurt', 'price' => '₦12,000', 'description' => 'Seasonal fruits with creamy yogurt.', 'tags' => ['L']],
            ],
        ],
        [
            'title' => 'Continental Soups',
            'items' => [
                ['name' => 'Laksa Soup', 'price' => '₦25,000', 'description' => 'Spicy coconut-based noodle soup.'],
                ['name' => 'Cream of Mushroom', 'price' => '₦21,000', 'tags' => ['L']],
                ['name' => 'Tom Yum', 'price' => '₦20,000'],
                ['name' => 'Potato Soup', 'price' => '₦19,000'],
                ['name' => 'Chicken Noodle Soup', 'price' => '₦17,000'],
                ['name' => 'Chicken & Sweetcorn Velouté', 'price' => '₦14,000'],
            ],
        ],
        [
            'title' => 'National Soups',
            'items' => [
                ['name' => 'Chicken Pepper Soup', 'price' => '₦15,000', 'description' => 'Aromatic, pepper-flavoured chicken broth with yam.'],
                ['name' => 'Cow Tail Pepper Soup', 'price' => '₦15,000', 'description' => 'Peppery cow tail broth with fragrant local spices.'],
                ['name' => 'Assorted Pepper Soup', 'price' => '₦37,177', 'description' => 'Peppery assorted meat broth with fragrant local spices.'],
                ['name' => 'Fish Pepper Soup', 'price' => '₦15,000', 'description' => 'Light, balanced set inspired by traditional Japanese flavors and presentation.'],
                ['name' => 'Goat Meat Pepper Soup', 'price' => '₦15,000', 'description' => 'Peppery goat broth with fragrant local spices.'],
                ['name' => 'Prawn Pepper Soup', 'price' => '₦15,000', 'description' => 'Mixed seafood peppery prawn broth with calamari and fragrant local spices.', 'tags' => ['S']],
                ['name' => 'Beef Pepper Soup', 'price' => '₦11,000', 'description' => 'Peppery beef broth with fragrant local spices.'],
            ],
        ],
        [
            'title' => 'Salads & Add-Ons',
            'items' => [
                ['name' => 'Coup Salad', 'price' => '₦15,000', 'description' => 'Refreshing mix of crisp lettuce, fresh tomatoes, cucumbers, carrots, and seasonal vegetables, tossed in a light house dressing.', 'tags' => ['V']],
                ['name' => 'Vegetable Salad', 'price' => '₦13,000', 'description' => 'Refreshing mix of crisp lettuce, fresh tomatoes, cucumbers, carrots, and seasonal vegetables, tossed in a light house dressing.', 'tags' => ['V']],
                ['name' => 'Oceanova Special Seafood Salad', 'price' => '₦12,000', 'description' => 'Chef’s signature salad made with seasonal ingredients and house dressing.', 'tags' => ['S']],
                ['name' => 'Classic Greek Salad', 'price' => '₦10,000', 'description' => 'Cucumber, tomatoes, onions, olives, lettuce, feta cheese, and herb vinaigrette.', 'tags' => ['V', 'L']],
                ['name' => 'Classic Tuna Caesar Salad', 'price' => '₦13,000', 'description' => 'Crisp lettuce, tuna, poached egg, Parmesan, croutons, and Caesar dressing.'],
                ['name' => 'Add Chicken', 'price' => '₦12,000'],
                ['name' => 'Add Prawns', 'price' => '₦8,500', 'tags' => ['S']],
            ],
        ],
        [
            'title' => 'Rice Dishes',
            'items' => [
                ['name' => 'Biryani Rice', 'price' => '₦25,000', 'description' => 'Slow-cooked basmati infused with chicken, yogurt, and traditional aromatic spices; served with sour sauce.'],
                ['name' => 'Mongolian Rice', 'price' => '₦18,000', 'description' => 'Flavorful fried rice tossed with vegetables in Mongolian-style sauce.'],
                ['name' => 'Coconut or Native Rice', 'price' => '₦16,000', 'description' => 'House signature rice with the chef’s secret blend of spices.'],
                ['name' => 'Oceanova Special Jollof Rice', 'price' => '₦16,000', 'description' => 'House signature rice with the chef’s secret blend of spices.'],
                ['name' => 'Oceanova Special Fried Rice', 'price' => '₦16,000', 'description' => 'Stir-fried rice with mixed seafood and garden vegetables.'],
                ['name' => 'Chinese Fried Rice', 'price' => '₦16,000', 'description' => 'Classic wok-fried rice with vegetables and light soy seasoning.'],
                ['name' => 'Seafood Fried Rice', 'price' => '₦23,000', 'description' => 'Stir-fried rice with assorted seafood and garden vegetables.', 'tags' => ['S']],
            ],
        ],
        [
            'title' => 'Pasta & Noodles',
            'items' => [
                ['name' => 'Chicken Alfredo', 'price' => '₦25,000', 'description' => 'Grilled chicken with a creamy Alfredo sauce and Parmesan.', 'tags' => ['L']],
                ['name' => 'Shrimp Fettuccine in Tomato Sauce', 'price' => '₦24,000', 'description' => 'Fettuccine with slow-cooked tomato sauce and succulent shrimp.', 'tags' => ['S', 'L']],
                ['name' => 'Pasta Carbonara', 'price' => '₦23,000', 'description' => 'Classic spaghetti with bacon, eggs, and Parmesan cream.', 'tags' => ['P', 'L']],
                ['name' => 'Penne Pesto', 'price' => '₦21,000', 'description' => 'Penne in a creamy basil pesto finished with Parmesan.', 'tags' => ['L', 'V']],
                ['name' => 'Bolognese Pasta', 'price' => '₦21,000', 'description' => 'Slow-cooked minced meat served over pasta with grated Parmesan.'],
                ['name' => 'Stir-Fried Singaporean Noodles', 'price' => '₦25,000', 'description' => 'Wok-tossed noodles with chicken, shrimp, and crisp vegetables in soy–oyster seasoning.'],
                ['name' => 'Seafood Lasagna', 'price' => '₦23,000', 'description' => 'Layered lasagna with mixed seafood, béchamel, and tomato ragu.', 'tags' => ['S', 'L']],
            ],
        ],
        [
            'title' => 'Main Courses',
            'subtitle' => 'Grill',
            'items' => [
                ['name' => 'English Fish & Chips', 'price' => '₦25,000', 'description' => 'Crispy battered fish served with French fries and tartar sauce.'],
                ['name' => 'Citrus Grilled Salmon Fillet', 'price' => '₦25,000', 'description' => 'Salmon fillet marinated in citrus herbs and grilled.', 'tags' => ['S']],
                ['name' => 'Jumbo Grilled Prawns', 'price' => '₦30,000', 'description' => 'Herb-marinated jumbo prawns finished with lemon butter.', 'tags' => ['S']],
                ['name' => 'Spicy Thai Shrimps', 'price' => '₦25,000', 'description' => 'Grilled shrimp in a spicy Thai glaze with fresh herbs.', 'tags' => ['S']],
                ['name' => 'Marinated Grilled Fish', 'price' => '₦21,000', 'description' => 'Fresh fish fillet marinated in house spices and flame-grilled.'],
                ['name' => 'Grill Lobster', 'price' => '₦25,000', 'description' => 'Premium lobster grilled to perfection with herbs and butter.', 'tags' => ['S']],
                ['name' => 'Porterhouse T-Bone Steak', 'price' => '₦30,000', 'description' => 'Premium porterhouse steak grilled to your preference.'],
                ['name' => 'Turkish Grilled Chicken Kebab', 'price' => '₦25,000', 'description' => 'Spiced chicken skewers served with flatbread and yogurt sauce.', 'tags' => ['L']],
                ['name' => 'Buttermilk Honey-Glazed Grilled Turkey', 'price' => '₦25,000', 'description' => 'Tender turkey marinated in buttermilk, grilled and glazed with honey butter.', 'tags' => ['L']],
            ],
        ],
        [
            'title' => 'National Dishes',
            'items' => [
                ['name' => 'Egusi Soup', 'price' => '₦14,000', 'description' => 'Melon-seed based soup, hearty and well-seasoned.'],
                ['name' => 'Banga Soup', 'price' => '₦17,000', 'description' => 'Rich palm-fruit-based soup with choice of protein.'],
                ['name' => 'Ofe Nsala Soup', 'price' => '₦16,000', 'description' => 'Traditional regional white soup prepared to chef’s specification; served with fish.'],
                ['name' => 'Fisherman Soup', 'price' => '₦16,000', 'description' => 'Hearty fish stew with market-fresh catches.'],
                ['name' => 'Edi Kai Kong Soup', 'price' => '₦17,000', 'description' => 'Specialty soup prepared to chef’s specification.'],
                ['name' => 'Ogbono Soup', 'price' => '₦17,000', 'description' => 'Thick, comforting ogbono soup served with swallow or rice.'],
                ['name' => 'Seafood Okro Soup', 'price' => '₦25,000', 'description' => 'Okro soup prepared with assorted seafood.'],
            ],
        ],
        [
            'title' => 'Shared Platters & Sides',
            'items' => [
                ['name' => 'Seafood Party Platter', 'price' => '₦30,000', 'description' => 'Mixed seafood with fries, sauces, and grilled vegetables — ideal for celebrations.', 'tags' => ['S']],
                ['name' => 'Oceanova Seafood Platter', 'price' => '₦25,000', 'description' => 'Generous selection of grilled fish, prawns, shrimp, calamari, and sides.', 'tags' => ['S']],
                ['name' => 'Small Chops Platter', 'price' => '₦30,000', 'description' => 'Spring rolls, samosas, puff-puff, and chicken wings with dipping sauces.'],
                ['name' => 'Big House Wings', 'price' => '₦25,000', 'description' => 'Crispy chicken wings tossed in Oceanova’s signature sauce, served with fries and dips.'],
                ['name' => 'Vegetarian Platter', 'price' => '₦21,000', 'description' => 'Grilled seasonal vegetables, plant-based proteins, and wholesome sides.', 'tags' => ['V']],
                ['name' => 'Coastal Grill Steak Platter', 'price' => '₦25,000', 'description' => 'Assorted grilled beef cuts with fries, vegetables, and sauces.'],
                ['name' => 'South-South Platter', 'price' => '₦21,000', 'description' => 'A Niger Delta tasting: seafood, traditional sides, and regional sauces.', 'tags' => ['S']],
            ],
        ],
        [
            'title' => 'Sides & Extras',
            'items' => [
                ['name' => 'Plantain Fries', 'price' => '₦7,000', 'description' => 'Sweet plantain, fried to caramelized edges.'],
                ['name' => 'Sweet Potato Fries', 'price' => '₦7,000', 'description' => 'Crisp sweet potato fries.'],
                ['name' => 'Yam Fries', 'price' => '₦5,000', 'description' => 'Golden fried yam sticks.'],
                ['name' => 'French Fries', 'price' => '₦5,000', 'description' => 'Crisp, twice-cooked fries.'],
                ['name' => 'Mashed Potatoes', 'price' => '₦5,000', 'description' => 'Creamy, hand-mashed with butter.', 'tags' => ['L']],
                ['name' => 'Coleslaw', 'price' => '₦6,000', 'description' => 'Shredded cabbage with a light creamy dressing.', 'tags' => ['L']],
                ['name' => 'Oceanova Special Jollof Rice', 'price' => '₦7,000', 'description' => 'Chef’s signature jollof with aromatic spices.'],
                ['name' => 'Prawns', 'price' => '₦7,000', 'description' => 'Additional portion of prawns.', 'tags' => ['S']],
                ['name' => 'Chicken', 'price' => '₦15,000', 'description' => 'Additional portion of grilled chicken.'],
                ['name' => 'Shrimp', 'price' => '₦18,500', 'description' => 'Additional portion of shrimp.', 'tags' => ['S']],
            ],
        ],
        [
            'title' => 'Red Wine',
            'items' => [
                ['name' => 'Declan', 'price' => '₦20,000', 'description' => 'Smooth and easy-drinking red wine.'],
                ['name' => 'Four Cousins (Dry)', 'price' => '₦20,000', 'description' => 'Medium-bodied dry red with soft tannins and fruity notes.'],
                ['name' => 'Carlo Rossi', 'price' => '₦25,000', 'description' => 'Well-balanced red wine with rich berry flavors.'],
                ['name' => 'Apothic', 'price' => '₦27,000', 'description' => 'Bold red blend with hints of dark fruit and vanilla.'],
                ['name' => '4th Street', 'price' => '₦18,000', 'description' => 'Light-bodied red wine with a smooth finish.'],
                ['name' => 'Asconi Agor', 'price' => '₦27,000', 'description' => 'Structured red wine with balanced acidity and fruit tones.'],
                ['name' => 'Massimo (Merlot / Cabernet Sauvignon)', 'price' => '₦45,000', 'description' => 'Premium red with rich character and layered flavors.'],
                ['name' => 'Escudo Rojo', 'price' => '₦40,000', 'description' => 'Full-bodied Chilean red with intense fruit and oak notes.'],
                ['name' => 'Nederburg (Cabernet Sauvignon)', 'price' => '₦35,000', 'description' => 'Classic Cabernet Sauvignon with deep berry and spice notes.'],
            ],
        ],
        [
            'title' => 'White Wine',
            'items' => [
                ['name' => 'Four Cousins (Dry)', 'price' => '₦18,000', 'description' => 'Fresh and fruity dry white wine with a smooth finish.'],
                ['name' => '4th Street', 'price' => '₦20,000', 'description' => 'Light-bodied white wine with crisp fruit notes.'],
                ['name' => 'Castillo Grande', 'price' => '₦27,000', 'description' => 'Well-balanced white wine with soft fruit aromas and a clean finish.'],
                ['name' => 'Nederburg (Sauvignon Blanc)', 'price' => '₦35,000', 'description' => 'Vibrant Sauvignon Blanc with citrus and tropical flavors.'],
                ['name' => 'Massimo', 'price' => '₦40,000', 'description' => 'Premium structured wine with refined character and smooth body.'],
                ['name' => 'Escudo Rojo', 'price' => '₦45,000', 'description' => 'Elegant, full-flavored wine with rich fruit expression.'],
                ['name' => 'Nederburg', 'price' => '₦35,000', 'description' => 'Classic, well-balanced wine with fresh acidity and layered fruit notes.'],
                ['name' => 'Clarendelle Bordeaux', 'price' => '₦50,000', 'description' => 'Elegant Bordeaux blend with refined acidity and balanced fruit character.'],
            ],
        ],
        [
            'title' => 'Liqueur',
            'items' => [
                ['name' => 'Baileys Irish Cream', 'price' => '₦35,000', 'description' => 'Creamy liqueur combining Irish whiskey and chocolate flavors.'],
                ['name' => 'Jägermeister', 'price' => '₦30,000', 'description' => 'Herbal liqueur with bold spices and a smooth finish.'],
                ['name' => 'Ivory Cream', 'price' => '₦27,000', 'description' => 'Sweet cream-based liqueur with a rich, smooth texture.'],
            ],
        ],
        [
            'title' => 'Tequila',
            'items' => [
                ['name' => 'Olmeca Tequila', 'price' => '₦45,000', 'description' => 'Smooth agave flavor with a lively and slightly peppery finish.'],
                ['name' => 'Sierra Tequila', 'price' => '₦37,000', 'description' => 'Fresh and vibrant tequila with light fruity notes.'],
            ],
        ],
        [
            'title' => 'Cognac',
            'items' => [
                ['name' => 'Martell VS', 'price' => '₦75,000', 'description' => 'A youthful cognac with fruity notes and a smooth oak finish.'],
                ['name' => 'Martell Blue Swift', 'price' => '₦140,000', 'description' => 'Modern cognac finished in bourbon casks with hints of vanilla and spice.'],
                ['name' => 'Hennessy VS', 'price' => '₦90,000', 'description' => 'Bold and vibrant cognac with toasted oak and fruit flavors.'],
                ['name' => 'Hennessy VSOP', 'price' => '₦150,000', 'description' => 'Mature, balanced cognac offering smooth spice and rich character.'],
            ],
        ],
        [
            'title' => 'Vodka',
            'items' => [
                ['name' => 'Sky Vodka', 'price' => '₦27,000', 'description' => 'Smooth and light with a clean finish, perfect for mixed drinks.'],
                ['name' => 'Absolut Vodka', 'price' => '₦30,000', 'description' => 'Premium Swedish vodka known for purity and balanced flavor.'],
                ['name' => 'Flirt Vodka', 'price' => '₦17,000', 'description' => 'Easy-drinking vodka with a soft, neutral profile.'],
            ],
        ],
        [
            'title' => 'Gin',
            'items' => [
                ['name' => 'Gordon’s Gin', 'price' => '₦30,000', 'description' => 'Classic London dry gin with juniper-forward notes.'],
                ['name' => 'Bombay Sapphire', 'price' => '₦40,000', 'description' => 'Smooth premium gin with floral and citrus botanicals.'],
            ],
        ],
        [
            'title' => 'Whiskey',
            'items' => [
                ['name' => 'Glenfiddich (12 Years)', 'price' => '₦90,000', 'description' => 'Single malt Scotch with pear, oak, and subtle sweetness.'],
                ['name' => 'Jameson', 'price' => '₦45,000', 'description' => 'Smooth Irish whiskey with vanilla and toasted wood notes.'],
                ['name' => 'Jameson Black Barrel', 'price' => '₦70,000', 'description' => 'Rich and intense whiskey with deeper spice and caramel tones.'],
                ['name' => 'Jack Daniel’s', 'price' => '₦50,000', 'description' => 'Classic Tennessee whiskey with sweet oak and smoky finish.'],
            ],
        ],
        [
            'title' => 'Cocktails',
            'items' => [
                ['name' => 'Margarita', 'price' => '₦12,900', 'description' => 'Tequila, Triple Sec, Lemon Juice, Simple Syrup'],
                ['name' => 'Blue Lagoon', 'price' => '₦10,750', 'description' => 'Vodka, Blue Curacao, Lime Juice, Simple Syrup'],
                ['name' => 'Mojito', 'price' => '₦10,750', 'description' => 'Rum, Sugar, Mint, Lime, Soda Water'],
                ['name' => 'Daiquiri', 'price' => '₦10,750', 'description' => 'Rum, Lemon Juice, Simple Syrup'],
                ['name' => 'Cosmopolitan', 'price' => '₦10,750', 'description' => 'Vodka, Triple Sec, Cranberry Juice, Lemon Juice'],
                ['name' => 'Tequila Sunrise', 'price' => '₦10,750', 'description' => 'Tequila, Orange Juice, Grenadine'],
                ['name' => 'Long Island Iced Tea', 'price' => '₦10,750', 'description' => 'Gin, Rum, Vodka, Tequila, Triple Sec, Lime Juice, Coke'],
                ['name' => 'Martini', 'price' => '₦10,750', 'description' => 'Gin or Vodka (classic preparation)'],
                ['name' => 'Sex on the Beach', 'price' => '₦10,750', 'description' => 'Vodka, Cranberry Juice, Orange Juice, Peach Schnapps'],
                ['name' => 'Piña Colada', 'price' => '₦10,750', 'description' => 'Rum, Coconut Cream, Pineapple Juice'],
                ['name' => 'Whiskey Sour', 'price' => '₦10,750', 'description' => 'Whiskey, Lemon Juice, Simple Syrup, Egg White'],
            ],
        ],
         [
            'title' => 'Mocktails',
            'items' => [
                ['name' => 'Shirley Temple', 'price' => '₦6,450.00', 'description' => 'Grenadine, Lemon Juice, Sprite'],
                ['name' => 'Virgin Bellini', 'price' => '₦8,600.00', 'description' => 'Flavoured Syrup, Lemon Syrup, Soda Water'],
                ['name' => 'Rainbow Paradise', 'price' => '₦8,600.00', 'description' => 'Grenadine, Orange Juice, Orange Soda, Citrus Soda, Bitters'],
                ['name' => 'Blue Ocean', 'price' => '₦7,525.00', 'description' => 'Blue Curacao, Lemon Juice, Simple Syrup, Sprite'],
                ['name' => 'Virgin Mojito', 'price' => '₦5,300.74', 'description' => 'Mint, Sugar, Lime, Soda'],
                ['name' => 'Iced Tea', 'price' => '₦8,600.00', 'description' => 'Simple Syrup, Lemon Juice, Tea Bag'],
                ['name' => 'Chapman', 'price' => '₦6,450.00', 'description' => 'Grenadine, Lemon Juice, Citrus Soda'],
                ['name' => 'Apple Cooler', 'price' => '₦8,600.00', 'description' => 'Apple Juice, Honey, Lemon Juice, Sprite'],
                ['name' => 'Citrus-Ginger Fritz', 'price' => '₦8,600.00', 'description' => 'Ginger Juice, Lemon Syrup, Honey, Sprite'],
                ['name' => 'Virgin Colada', 'price' => '₦5,300.74', 'description' => 'Coconut Blend, Pineapple Juice, Cream'],
                ['name' => 'Lemonade', 'price' => '₦7,525.00', 'description' => 'Lemon Juice, Simple Syrup, Soda'],
            ],
        ],
        
    ];
    }

    $sections = array_map(function (array $section): array {
        $sectionSlug = Str::slug((string) ($section['title'] ?? 'menu'));

        $section['items'] = array_map(function (array $item) use ($sectionSlug): array {
            if (!empty($item['image'])) {
                return $item;
            }

            $itemSlug = Str::slug((string) ($item['name'] ?? 'item'));
            $item['image'] = "images/menu/{$sectionSlug}-{$itemSlug}.jpg";

            return $item;
        }, $section['items'] ?? []);

        return $section;
    }, $sections ?? []);
@endphp

<script>
    window.tailwind = window.tailwind || {};
    window.tailwind.config = {
        important: '#tw-menu',
        corePlugins: {
            preflight: false,
            collapse: false,
        },
    };
</script>
<script src="https://cdn.tailwindcss.com"></script>
<style>
    #ftco-nav.collapse,
    #ftco-nav.collapsing {
        visibility: visible !important;
    }

    /* Menu page only: gold navbar when scrolled/awake */
    #ftco-navbar.ftco-navbar-light.scrolled.awake {
        background: #c9a227 !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    #ftco-navbar.ftco-navbar-light.scrolled.awake .nav-link,
    #ftco-navbar.ftco-navbar-light.scrolled.awake .navbar-brand {
        color: #111 !important;
    }

    #ftco-navbar.ftco-navbar-light.scrolled.awake .nav-item.active > .nav-link {
        color: #fff !important;
    }

    /* Mobile only: old nav slides away in this page too */
    @media (max-width: 991.98px) {
        #ftco-navbar.ftco-navbar-light.scrolled.awake {
            z-index: 40 !important;
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
    }

    @media print {
        html,
        body,
        #tw-menu,
        #tw-menu * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #tw-menu .menu-pdf-bg {
            opacity: 0.22 !important;
        }

        #tw-menu .menu-pdf-section {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        #tw-menu .menu-pdf-card {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
</style>

<div id="tw-menu">

<section class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('assets/template/images/bg_5.jpg') }}');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
            <div class="col-md-9 ftco-animate text-center mb-5">
                <h1 class="mb-2 bread">Menu</h1>
                <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('home') }}">Home <i class="fa fa-chevron-right"></i></a></span> <span>Menu <i class="fa fa-chevron-right"></i></span></p>
            </div>
        </div>
    </div>
</section>

<section class="min-h-screen bg-black relative">
    <div class="menu-pdf-bg absolute inset-0 pointer-events-none opacity-20 mix-blend-screen" style="background-image: url('{{ asset('images/oceanova.png') }}'); background-repeat: repeat; background-size: 260px auto; background-position: center top;"></div>

    <div class="relative z-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-10 pb-6">
        <div class="flex flex-col gap-3">
            <span class="text-xs uppercase tracking-[0.3em] text-amber-300">Oceanova Digital Menu</span>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-2xl sm:text-3xl font-semibold text-white">Breakfast, soups, mains and platters</h2>
                <div class="flex flex-wrap items-center gap-3 text-xs text-amber-100">
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 2c3.5 4.5 5 7 5 9a5 5 0 0 1-10 0c0-2 1.5-4.5 5-9z" />
                        </svg>
                        V Vegetarian
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-amber-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 2v20" />
                            <path d="M8 6h8" />
                        </svg>
                        L Lactose
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-rose-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 11h18" />
                            <path d="M5 7h14" />
                            <path d="M7 15h10" />
                        </svg>
                        P Pork
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 text-sky-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 10c1-2 4-3 7-3 2.5 0 5 .7 5 2.5S16 13 13.5 13H10" />
                            <path d="M3 13c0 3 3 5 7 5 5 0 8-2 8-5" />
                        </svg>
                        S Seafood
                    </span>
                </div>
            </div>
            <p class="text-sm text-amber-100 max-w-2xl">
                PDF-ready menu view with full backgrounds and rendered meal images.
            </p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        @php($mealNumber = 1)
        @foreach($sections as $section)
            <div id="{{ Str::slug($section['title']) }}" class="menu-pdf-section mb-10 scroll-mt-24">
                <h3 class="text-xl font-semibold text-amber-300 mb-1">{{ $section['title'] }}</h3>
                @if(!empty($section['subtitle']))
                    <p class="text-sm text-amber-100 mb-4">{{ $section['subtitle'] }}</p>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($section['items'] as $item)
                        <x-menu-item
                            class="menu-pdf-card"
                            :number="$mealNumber"
                            :name="$item['name']"
                            :price="$item['price'] ?? null"
                            :description="$item['description'] ?? null"
                            :image="$item['image'] ?? null"
                            :tags="$item['tags'] ?? []"
                        />
                        @php($mealNumber++)
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    </div>
</section>
</div>

<script>
    (function () {
        var prepared = false;

        function toJpegDataUrl(img, maxWidth, quality) {
            try {
                var naturalWidth = img.naturalWidth || img.width;
                var naturalHeight = img.naturalHeight || img.height;

                if (!naturalWidth || !naturalHeight) {
                    return null;
                }

                var targetWidth = Math.min(maxWidth, naturalWidth);
                var targetHeight = Math.round((targetWidth / naturalWidth) * naturalHeight);

                var canvas = document.createElement('canvas');
                canvas.width = targetWidth;
                canvas.height = targetHeight;

                var ctx = canvas.getContext('2d', { alpha: false });
                if (!ctx) {
                    return null;
                }

                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, targetWidth, targetHeight);
                ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

                return canvas.toDataURL('image/jpeg', quality);
            } catch (error) {
                return null;
            }
        }

        async function prepareImagesForPrint() {
            if (prepared) {
                return;
            }

            var images = Array.prototype.slice.call(document.querySelectorAll('#tw-menu .menu-pdf-card img'));
            if (!images.length) {
                prepared = true;
                return;
            }

            await Promise.all(images.map(function (img) {
                if (img.complete) {
                    return Promise.resolve();
                }

                return new Promise(function (resolve) {
                    img.addEventListener('load', function onLoad() {
                        img.removeEventListener('load', onLoad);
                        resolve();
                    });
                    img.addEventListener('error', function onError() {
                        img.removeEventListener('error', onError);
                        resolve();
                    });
                });
            }));

            images.forEach(function (img) {
                if (!img.dataset.originalSrc) {
                    img.dataset.originalSrc = img.currentSrc || img.src;
                }

                var dataUrl = toJpegDataUrl(img, 480, 0.58);
                if (dataUrl) {
                    img.src = dataUrl;
                }
            });

            prepared = true;
        }

        function restoreImagesAfterPrint() {
            var images = document.querySelectorAll('#tw-menu .menu-pdf-card img[data-original-src]');
            images.forEach(function (img) {
                img.src = img.dataset.originalSrc;
            });
            prepared = false;
        }

        window.addEventListener('beforeprint', function () {
            prepareImagesForPrint();
        });

        window.addEventListener('afterprint', function () {
            restoreImagesAfterPrint();
        });
    })();
</script>
@endsection
