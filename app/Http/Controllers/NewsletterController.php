<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterWelcome;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $validated['email']],
            ['last_sent_at' => null]
        );

        if ($subscriber->wasRecentlyCreated) {
            Mail::to($subscriber->email)->send(new NewsletterWelcome());
        }

        return back()->with('success', 'Thanks for subscribing to Oceanova updates.');
    }
}
