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


        // Use the submitted value directly for table_label
        $tableLabel = $validated['noofv'];

        // Extract guest count from the label (e.g., 'Table 01 - 4 Guests' => 4)
        $guestCount = null;
        if (preg_match('/(\d+)\s*Guests?/', $tableLabel, $matches)) {
            $guestCount = (int) $matches[1];
        }

        try {
            // Save booking to database before sending initial emails
            $booking = \App\Models\Booking::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'whatsapp_number' => $validated['tel'],
                'guest_count' => $guestCount,
                'table_id' => $tableLabel,
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
