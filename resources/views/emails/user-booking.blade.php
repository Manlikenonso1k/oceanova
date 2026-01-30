@extends('emails.layout')

@section('content')
    <h2 style="margin:0 0 12px;color:#111111;">Thank you for your booking</h2>
    <p style="margin:0 0 16px;">We’ve received your booking request. Here is your summary:</p>

    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:14px;">
        <tr>
            <td style="padding:8px 0;color:#666;">Full Name</td>
            <td style="padding:8px 0;color:#111;">{{ $booking['full_name'] }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#666;">Email</td>
            <td style="padding:8px 0;color:#111;">{{ $booking['email'] }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#666;">Phone</td>
            <td style="padding:8px 0;color:#111;">{{ $booking['tel'] }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#666;">Guests</td>
            <td style="padding:8px 0;color:#111;">{{ $booking['noofv'] }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#666;">Check-In</td>
            <td style="padding:8px 0;color:#111;">{{ $booking['signin'] }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;color:#666;">Check-Out</td>
            <td style="padding:8px 0;color:#111;">{{ $booking['signout'] }}</td>
        </tr>
    </table>

    <p style="margin:16px 0 0;">We’ll contact you shortly to confirm your booking.</p>
@endsection
