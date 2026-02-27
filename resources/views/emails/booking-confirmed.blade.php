@extends('emails.layout')

@section('content')
    <div style="background:#000;color:#d4af37;padding:16px;border-radius:6px;">
        <h2 style="margin:0 0 12px;color:#d4af37;">Booking Confirmed — Oceanova</h2>
        <p style="color:#fff;">Thank you for booking with Oceanova. Your reservation has been confirmed for our Grand Opening.</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:14px;color:#fff;">
            <tr>
                <td style="padding:8px 0;color:#d4af37;">Name</td>
                <td style="padding:8px 0;">{{ $booking['name'] ?? $booking['full_name'] }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;color:#d4af37;">Table</td>
                <td style="padding:8px 0;">{{ $booking['table_label'] ?? $booking['table'] ?? 'Assigned on arrival' }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;color:#d4af37;">Date</td>
                <td style="padding:8px 0;">February 28, 2026</td>
            </tr>
            <tr>
                <td style="padding:8px 0;color:#d4af37;">Time</td>
                <td style="padding:8px 0;">{{ $booking['booking_time'] ?? $booking['signout'] ?? '' }}</td>
            </tr>
        </table>

        <p style="color:#fff;margin-top:12px;">We look forward to welcoming you — Oceanova Restaurant.</p>
    </div>
@endsection
