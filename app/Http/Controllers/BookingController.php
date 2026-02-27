<?php

namespace App\Http\Controllers;

use App\Mail\AdminBookingNotification;
use App\Mail\UserBookingConfirmation;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

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

        // Normalize and clamp dates
        try {
            $maxDate = Carbon::create(2026, 2, 25)->endOfDay();
            $signin = Carbon::parse($validated['signin']);
            if ($signin->gt($maxDate)) {
                $signin = $maxDate;
            }
            $validated['signin'] = $signin->format('Y-m-d');
        } catch (\Exception $e) {
            // leave as provided if parsing fails
        }


        try {
            $signout = Carbon::parse($validated['signout']);
            $validated['signout'] = $signout->format('H:i:s');
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
            '9' => 'Table 10 - 4 Guests(outdoor)',
            '10' => 'Table 11 - 4 Guests(outdoor)',
            '11' => 'Table 12 - 4 Guests(outdoor)',
            '12' => 'Table 13 - 4 Guests(outdoor)',
        ];

        $tableLabel = $tableMap[$validated['noofv']] ?? null;

        try {
            // Save booking to database before sending initial emails
            $booking = \App\Models\Booking::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'whatsapp_number' => $validated['tel'],
                'guest_count' => is_numeric($validated['noofv']) ? intval($validated['noofv']) : null,
                'table_id' => $validated['noofv'],
                'table_label' => $tableLabel,
                'booking_date' => $validated['signin'] ?? null,
                'booking_time' => $validated['signout'] ?? null,
                'status' => 'pending',
            ]);

            $payload = array_merge($validated, $booking->toArray());

            Mail::to($adminRecipients)->send(new AdminBookingNotification($payload));
            Mail::to($validated['email'])->send(new UserBookingConfirmation($payload));

            return back()->with('success', 'Your booking request has been sent.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000' && str_contains($e->getMessage(), 'bookings_email_unique')) {
                return back()->with('error', 'You have already booked with this email address.');
            }
            throw $e;
        }
    }

    public function index()
    {
        $user = Auth::user();

        if (! $user || ! $user->hasAnyRole(['admin', 'super_admin', 'general_manager'])) {
            abort(403);
        }

        $bookings = \App\Models\Booking::orderBy('created_at', 'desc')->paginate(25);

        return view('bookings.index', compact('bookings'));
    }
}
