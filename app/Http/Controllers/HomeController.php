<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $featuredMeals = Meal::query()
            ->with('menuSection')
            ->where('is_active', true)
            ->where('is_hidden', false)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(12)
            ->get()
            ->map(function (Meal $meal) {
                $price = (float) $meal->price;
                $decimals = abs($price - floor($price)) < 0.00001 ? 0 : 2;

                $imageUrl = asset('assets/template/images/breakfast-1.jpg');

                if (!empty($meal->image)) {
                    if (Str::startsWith($meal->image, ['http://', 'https://', '/'])) {
                        $imageUrl = $meal->image;
                    } elseif (Str::startsWith($meal->image, ['assets/', 'images/', 'storage/'])) {
                        $imageUrl = asset($meal->image);
                    } else {
                        $imageUrl = Storage::disk('public')->url($meal->image);
                    }
                }

                return [
                    'name' => $meal->name,
                    'price' => '₦'.number_format($price, $decimals),
                    'description' => $meal->description,
                    'section' => $meal->menuSection?->title,
                    'image_url' => $imageUrl,
                ];
            })
            ->toArray();

        return view('home', compact('featuredMeals'));
    }
}
