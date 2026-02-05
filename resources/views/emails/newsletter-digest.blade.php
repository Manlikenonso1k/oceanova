@extends('emails.layout')

@section('content')
    <h2 style="margin: 0 0 12px; font-size: 22px; color: #222;">{{ $headline }}</h2>
    <p style="margin: 0 0 16px; color: #555; line-height: 1.6;">
        {{ $intro }}
    </p>
    <ul style="margin: 0 0 18px; padding-left: 18px; color: #555;">
        <li>Seasonal menu highlights and chef specials</li>
        <li>Private dining dates and tasting events</li>
        <li>Priority reservation openings</li>
    </ul>
    <p style="margin: 0 0 20px;">
        <a href="{{ $ctaUrl ?: route('reservation') }}" style="display: inline-block; background: #f96d00; color: #fff; text-decoration: none; padding: 10px 18px; border-radius: 4px;">
            {{ $ctaText }}
        </a>
    </p>
    <p style="margin: 0; color: #777; font-size: 12px;">You are receiving this email because you subscribed on Oceanova.</p>
@endsection
