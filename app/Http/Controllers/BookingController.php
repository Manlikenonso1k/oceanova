<?php

namespace App\Http\Controllers;

use App\Mail\AdminBookingNotification;
use App\Mail\UserBookingConfirmation;
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
            'booking@icelandbeach.com',
            'v.chinonso@collegeofartslagos.com',
            'akapo@icelandbeach.com',
            'info@icelandbeach.com',
        ];

        Mail::to($adminRecipients)->send(new AdminBookingNotification($validated));
        Mail::to($validated['email'])->send(new UserBookingConfirmation($validated));

        return back()->with('success', 'Your booking request has been sent.');
    }
}
