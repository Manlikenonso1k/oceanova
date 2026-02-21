@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;

    $sections = [
        [
            'title' => 'Breakfast Selections',
            'subtitle' => 'International & Nigerian Classics',
            'items' => [
                ['name' => 'English Breakfast', 'price' => '₦18,347', 'description' => 'Eggs, sausage, grilled tomato, baked beans, toast.', 'tags' => ['P']],
                ['name' => 'French Traditional Potato Omelette', 'price' => '₦21,500', 'description' => 'Potato, bacon, herbs, and toast.', 'tags' => ['P']],
                ['name' => 'Designed Oatmeal', 'price' => '₦8,900', 'description' => 'Creamy oatmeal, seasonal fruits, and brown sugar.', 'tags' => ['V', 'L']],
                ['name' => 'Boiled Yam/Plantain', 'price' => '₦6,000', 'description' => 'Served with egg or fish sauce.'],
                ['name' => 'Ewa Agoyin', 'price' => '₦9,500', 'description' => 'Served with bread or plantain.'],
                ['name' => 'Pap & Moi Moi', 'price' => '₦5,000'],
                ['name' => 'Pap with Akara or Okpa', 'price' => '₦6,500'],
                ['name' => 'Congee (Zhōu)', 'price' => '₦5,300', 'description' => 'Savory rice porridge with pork and pickles.', 'tags' => ['P']],
                ['name' => 'Youtiao & Soy Milk', 'price' => '₦5,300', 'description' => 'Deep-fried dough sticks with sweet/savory soy milk.'],
                ['name' => 'Spicy Noodles', 'price' => '₦5,300', 'description' => 'Regional morning noodles (Dan Dan style).'],
                ['name' => 'Chicken & Waffles', 'price' => '₦14,000', 'description' => 'Milk-fried chicken on golden waffles.', 'tags' => ['L']],
                ['name' => 'Blueberry Pancakes', 'price' => '₦11,000', 'tags' => ['L']],
                ['name' => 'Plain Pancakes', 'price' => '₦10,000'],
                ['name' => 'Chocolate Pancakes', 'price' => '₦9,500'],
                ['name' => 'Mixed Fruits & Yogurt', 'price' => '₦12,000', 'description' => 'Seasonal fruits with creamy yogurt.', 'tags' => ['L']],
            ],
        ],
        [
            'title' => 'Starters & Soups',
            'subtitle' => 'Continental and National Pepper Soups',
            'items' => [
                ['name' => 'Laksa Soup', 'price' => '₦25,000', 'description' => 'Spicy coconut-based noodle soup.'],
                ['name' => 'Cream of Mushroom', 'price' => '₦21,000', 'tags' => ['L']],
                ['name' => 'Tom Yum', 'price' => '₦20,000'],
                ['name' => 'Potato Soup', 'price' => '₦19,000'],
                ['name' => 'Chicken Noodle Soup', 'price' => '₦17,000'],
                ['name' => 'Chicken & Sweetcorn Velouté', 'price' => '₦14,000'],
                ['name' => 'Snail Pepper Soup', 'price' => '₦38,163', 'tags' => ['S']],
                ['name' => 'Prawn Pepper Soup', 'price' => '₦37,177', 'tags' => ['S']],
                ['name' => 'Cow Tail Pepper Soup', 'price' => '₦16,000'],
                ['name' => 'Chicken / Fish / Goat / Assorted / Native Pepper Soup', 'price' => '₦15,000'],
                ['name' => 'Beef Pepper Soup', 'price' => '₦11,000'],
            ],
        ],
        [
            'title' => 'Salads & Add-Ons',
            'items' => [
                ['name' => 'Oceanova Special Seafood Salad', 'price' => '₦17,500', 'tags' => ['S']],
                ['name' => 'Classic Greek Salad', 'price' => '₦16,000', 'tags' => ['V', 'L']],
                ['name' => 'Coup Salad / Vegetable Salad', 'price' => '₦15,000', 'tags' => ['V']],
                ['name' => 'Classic Tuna Caesar', 'price' => '₦13,000'],
                ['name' => 'Add Chicken', 'price' => '₦12,000'],
                ['name' => 'Add Prawns', 'price' => '₦8,500', 'tags' => ['S']],
            ],
        ],
        [
            'title' => 'Main Courses',
            'subtitle' => 'Rice, Pasta & Grill',
            'items' => [
                ['name' => 'Biryani Rice', 'price' => '₦25,000', 'description' => 'Slow-cooked aromatic basmati.'],
                ['name' => 'Seafood Fried Rice', 'price' => '₦23,000', 'tags' => ['S']],
                ['name' => 'Mongolian / Chinese Fried / Oceanova Special Jollof', 'price' => '₦18,000'],
                ['name' => 'Chicken Alfredo', 'price' => '₦30,000', 'description' => 'Creamy Parmesan sauce.', 'tags' => ['L']],
                ['name' => 'Stir-Fried Singaporean Noodles', 'price' => '₦25,000', 'tags' => ['S']],
                ['name' => 'Shrimp Fettuccine / Carbonara', 'price' => '₦24,000', 'tags' => ['P']],
                ['name' => 'Seafood Lasagna', 'price' => '₦23,000', 'tags' => ['S']],
                ['name' => 'Penne Pesto', 'price' => '₦21,000', 'tags' => ['V']],
                ['name' => 'Porterhouse T-Bone Steak / Jumbo Prawns / Grilled Salmon', 'price' => '₦30,000', 'tags' => ['S']],
                ['name' => 'Turkish Chicken Kebab / Spicy Thai Shrimps / Fish & Chips', 'price' => '₦25,000', 'tags' => ['S']],
                ['name' => 'Marinated Grilled Fish / Honey-Glazed Turkey', 'price' => '₦21,000'],
            ],
        ],
        [
            'title' => 'National Dishes',
            'items' => [
                ['name' => 'Egusi Soup', 'price' => '₦17,000', 'description' => 'Melon-seed based soup, hearty and well-seasoned.'],
                ['name' => 'Banga Soup', 'price' => '₦17,000', 'description' => 'Rich palm-fruit based soup with choice of protein.'],
                ['name' => 'Ofe Nsala Soup', 'price' => '₦16,000', 'description' => 'Light, fragrant white soup traditionally served with fish.'],
                ['name' => 'Fisherman Soup', 'price' => '₦35,000', 'description' => 'Hearty fish stew with market-fresh catches.'],
                ['name' => 'Edikang Ikong Soup', 'price' => '₦16,000', 'description' => 'Traditional regional vegetable soup prepared to chef’s specification.'],
                ['name' => 'Ogbono Soup', 'price' => '₦14,000', 'description' => 'Thick, comforting ogbono soup served with swallow or rice.'],
                ['name' => 'Seafood Okro Soup', 'price' => '₦25,000', 'description' => 'Okro soup prepared with assorted seafood.'],
            ],
        ],
        [
            'title' => 'Shared Platters & Sides',
            'items' => [
                ['name' => 'Seafood Party Platter / Oceanova Seafood Platter', 'price' => '₦30,000', 'tags' => ['S']],
                ['name' => 'Small Chops / Big House Wings / Coastal Grill Steak', 'price' => '₦25,000'],
                ['name' => 'South-South Platter', 'price' => '₦21,000'],
                ['name' => 'Sides', 'price' => '₦5,000 - ₦7,000', 'description' => 'Fries (Yam, Sweet Potato, French), Plantain, Mashed Potatoes, Jollof Rice.'],
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

    /* Mobile only: old nav slides away, category sticky takes top */
    @media (max-width: 991.98px) {
        #ftco-navbar.ftco-navbar-light.scrolled.awake {
            z-index: 40 !important;
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        #tw-menu .menu-sticky-mobile {
            top: 0 !important;
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
    <div class="absolute inset-0 pointer-events-none opacity-20 mix-blend-screen" style="background-image: url('{{ asset('images/oceanova.png') }}'); background-repeat: repeat; background-size: 260px auto; background-position: center top;"></div>

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
                Browse our price tiers for a sleek, mobile-first menu experience inspired by ServeWithTabul.
            </p>
        </div>
    </div>

    <div class="menu-sticky-mobile sticky top-0 z-[70] border-y border-amber-300/30 bg-black/95 backdrop-blur" style="position: sticky;">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex gap-3 overflow-x-auto md:overflow-visible md:flex-wrap md:justify-center py-3">
                @foreach($sections as $section)
                    <a href="#{{ Str::slug($section['title']) }}" class="whitespace-nowrap rounded-full border border-amber-300/40 bg-black px-4 py-2 text-sm font-medium text-amber-100 hover:border-amber-300 hover:text-amber-300 transition">
                        {{ $section['title'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        @foreach($sections as $section)
            <div id="{{ Str::slug($section['title']) }}" class="mb-10 scroll-mt-24">
                <h3 class="text-xl font-semibold text-amber-300 mb-1">{{ $section['title'] }}</h3>
                @if(!empty($section['subtitle']))
                    <p class="text-sm text-amber-100 mb-4">{{ $section['subtitle'] }}</p>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($section['items'] as $item)
                        <x-menu-item
                            :name="$item['name']"
                            :price="$item['price'] ?? null"
                            :description="$item['description'] ?? null"
                            :tags="$item['tags'] ?? []"
                        />
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    </div>
</section>
</div>
@endsection
