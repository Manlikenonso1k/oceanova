<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="{{ asset('oceanova-fav-icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('oceanova-fav-icon.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('oceanova-fav-icon.png') }}">

        <title>@yield('title')</title>

        <style>
            html,
            body {
                margin: 0;
                min-height: 100%;
                font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
                background: #f8fafc;
                color: #1f2937;
            }

            .wrap {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
            }

            .card {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                padding: 24px;
                max-width: 680px;
                width: 100%;
                display: flex;
                gap: 16px;
                align-items: baseline;
            }

            .code {
                font-size: 24px;
                font-weight: 700;
                border-right: 1px solid #d1d5db;
                padding-right: 16px;
                min-width: 72px;
            }

            .message {
                font-size: 20px;
                text-transform: uppercase;
                letter-spacing: 0.04em;
                color: #4b5563;
            }
        </style>
    </head>
    <body>
        <main class="wrap" role="main">
            <section class="card">
                <div class="code">@yield('code')</div>
                <div class="message">@yield('message')</div>
            </section>
        </main>
    </body>
</html>