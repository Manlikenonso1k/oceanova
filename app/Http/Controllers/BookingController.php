<?php

namespace App\Http\Controllers;

use App\Mail\AdminBookingNotification;
use App\Mail\UserBookingConfirmation;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['nullable', 'string'],
            'room_id' => ['nullable', 'string'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'tel' => ['required', 'string', 'max:50'],
            'noofv' => ['required', 'string', 'max:50'],
            'signin' => ['required', 'string', 'max:100'],
            'signout' => ['required', 'string', 'max:100'],
        ]);

        $adminRecipients = [
            'enquiries@oceanova.ng',
            'v.chinonso@collegeofartslagos.com',
            'booking@oceanova.ng'
        ];

        // Normalize and clamp dates, compute table assignment
        try {
            $maxDate = Carbon::create(2026, 2, 25)->endOfDay();
            $signin = Carbon::parse($validated['signin']);
            if ($signin->gt($maxDate)) {
                $signin = $maxDate;
            }
            $validated['signin'] = $signin->format('F j, Y');
        } catch (\Exception $e) {
            // leave as provided if parsing fails
        }

        try {
            $signout = Carbon::parse($validated['signout']);
            $validated['signout'] = $signout->format('g:ia');
        } catch (\Exception $e) {
            // leave as provided
        }

        // Map the select value to the human-readable table label (matches form options)
        $tableMap = [
            '1' => 'Table 02 - 4 Guests',
            '2' => 'Table 03 - 4 Guests',
            '3' => 'Table 04 - 4 Guests',
            '4' => 'Table 05 - 4 Guests',
            '5' => 'Table 06 - 2 Guests',
            '6' => 'Table 07 - 2 Guests',
            '7' => 'Table 08 - 2 Guests',
            '8' => 'Table 09 - 2 Guests',
            '9' => 'Table 10 - 3 Guests(outdoor)',
            '10' => 'Table 11 - 3 Guests(outdoor)',
            '11' => 'Table 12 - 3 Guests(outdoor)',
            '12' => 'Table 13 - 3 Guests(outdoor)',
        ];

        $validated['table'] = $tableMap[$validated['noofv']] ?? 'Table 01 - 4 Guests';

        Mail::to($adminRecipients)->send(new AdminBookingNotification($validated));
        Mail::to($validated['email'])->send(new UserBookingConfirmation($validated));

        return back()->with('success', 'Your booking request has been sent.');
    }
}
