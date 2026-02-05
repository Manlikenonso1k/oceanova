@extends('emails.layout')

@section('content')
    <div style="text-align: center;">
        <h2 style="margin: 0 0 12px; font-size: 22px; color: #222;">Welcome to Oceanova</h2>
        <p style="margin: 0 0 16px; color: #555; line-height: 1.6;">
            Thanks for subscribing. You’ll be the first to hear about seasonal menus, chef specials, and reservation openings.
        </p>
        <p style="margin: 0 0 20px;">
            <a href="{{ route('reservation') }}" style="display: inline-block; background: #f96d00; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 4px;">
                Reserve a table
            </a>
        </p>
        <p style="margin: 0; color: #777; font-size: 12px;">If you didn’t subscribe, you can ignore this email.</p>
    </div>
@endsection
