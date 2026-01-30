<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Booking Notification' }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f5f5;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5;padding:24px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                <tr>
                    <td style="background-image:url('{{ asset('assets/template/images/bg_1.jpg') }}');background-size:cover;background-position:center;padding:28px 32px;">
                        <div style="background:rgba(0,0,0,0.55);padding:16px;border-radius:6px;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;letter-spacing:1px;">Taste.it</h1>
                            <p style="margin:6px 0 0;color:#f1f1f1;font-size:14px;">Restaurant Booking</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px 32px;color:#333333;">
                        @yield('content')
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px 32px;background:#111111;color:#cccccc;font-size:12px;text-align:center;">
                        <p style="margin:0;">&copy; {{ date('Y') }} Taste.it. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
