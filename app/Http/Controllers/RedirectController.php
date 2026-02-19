<?php

namespace App\Http\Controllers;

use App\Models\Click;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'link_name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2048'],
        ]);

        Click::create([
            'link_name' => $validated['link_name'],
            'url' => $validated['url'],
            'clicked_at' => now(),
        ]);

        return redirect()->away($validated['url']);
    }
}
