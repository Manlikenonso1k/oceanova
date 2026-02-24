<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Contracts\View\View;

class ProcurementTemplateController extends Controller
{
    public function __invoke(): View
    {
        $ingredients = Ingredient::query()
            ->select(['id', 'name', 'category', 'unit'])
            ->orderBy('name')
            ->get();

        return view('procurement.live-template', [
            'ingredients' => $ingredients,
            'today' => now()->toDateString(),
        ]);
    }
}
