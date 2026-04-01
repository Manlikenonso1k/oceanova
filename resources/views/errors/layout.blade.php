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
                background: #ffffff;
                color: #4b5563;
            }

            .wrap {
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 24px;
            }

            .message {
                font-size: 36px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <main class="wrap">
            <div class="message">@yield('message')</div>
        </main>
    </body>
</html>