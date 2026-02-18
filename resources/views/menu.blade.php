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
                ['name' => 'National Dish Selection', 'price' => '₦14,000 - ₦25,000', 'description' => 'Choice of protein/swallow.'],
                ['name' => 'Available Selections', 'description' => 'Banga, Ogbono, Egusi, Afang, Edi Kai Kong, Fisherman Soup, Seafood Okro.'],
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

<section class="min-h-screen bg-black">
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

    <div class="sticky top-0 z-30 border-y border-amber-300/30 bg-black/95 backdrop-blur">
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
</section>
</div>
@endsection
