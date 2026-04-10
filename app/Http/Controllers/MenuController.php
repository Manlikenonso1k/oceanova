<?php

namespace App\Http\Controllers;

use App\Models\MenuSection;

class MenuController extends Controller
{
    public function index()
    {
        $sections = $this->menuSections();

        return view('menu', compact('sections'));
    }

    public function pdfView()
    {
        $sections = $this->menuSections();

        return view('menu-pdf', compact('sections'));
    }

    private function menuSections(): array
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
                $fallbackNumber = 1;

                return [
                    'title' => $section->title,
                    'subtitle' => $section->subtitle,
                    'items' => $section->meals->map(function ($meal) use (&$fallbackNumber) {
                        $fallback = $fallbackNumber;
                        $fallbackNumber++;

                        $price = (float) $meal->price;
                        $decimals = abs($price - floor($price)) < 0.00001 ? 0 : 2;

                        return [
                            'number' => $meal->sort_order > 0 ? (int) $meal->sort_order : $fallback,
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

        return $sections;
    }
}
