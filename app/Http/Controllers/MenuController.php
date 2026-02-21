<?php

namespace App\Http\Controllers;

use App\Models\MenuSection;

class MenuController extends Controller
{
    public function index()
    {
        $sections = MenuSection::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with(['meals' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('id');
            }])
            ->get()
            ->map(function (MenuSection $section) {
                return [
                    'title' => $section->title,
                    'subtitle' => $section->subtitle,
                    'items' => $section->meals->map(function ($meal) {
                        $price = (float) $meal->price;
                        $decimals = abs($price - floor($price)) < 0.00001 ? 0 : 2;

                        return [
                            'name' => $meal->name,
                            'price' => '₦'.number_format($price, $decimals),
                            'description' => $meal->description,
                            'image' => $meal->image,
                            'tags' => $meal->tags ?? [],
                        ];
                    })->toArray(),
                ];
            })
            ->toArray();

        return view('menu', compact('sections'));
    }
}
