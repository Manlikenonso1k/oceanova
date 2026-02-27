@extends('emails.layout')

@section('content')
    <div style="background:#000;color:#d4af37;padding:16px;border-radius:6px;">
        <h2 style="margin:0 0 12px;color:#d4af37;">Booking Update — Oceanova</h2>
        <p style="color:#fff;">We regret to inform you that your booking request has not been accepted.</p>
        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:14px;color:#fff;">
            <tr>
                <td style="padding:8px 0;color:#d4af37;">Name</td>
                <td style="padding:8px 0;">{{ $booking['name'] ?? $booking['full_name'] }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;color:#d4af37;">Table</td>
                <td style="padding:8px 0;">{{ $booking['table_label'] ?? $booking['table'] ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;color:#d4af37;">Reason</td>
                <td style="padding:8px 0;color:#fff;">{{ $booking['rejection_reason'] ?? 'No reason provided' }}</td>
            </tr>
        </table>

        <p style="color:#fff;margin-top:12px;">If you have questions, please contact us at 0708 282 0267.</p>
    </div>
@endsection
