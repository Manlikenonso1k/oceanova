<p align="center">
  <img src="public/images/oceanova.png" width="160" alt="Oceanova Logo">
</p>

# Oceanova Restaurant (Laravel)

Laravel integration of the Taste.it Bootstrap template with booking email notifications.

## Features
- Blade layout + pages wired from the template
- Centralized assets under public/assets/template
- Booking form that sends admin notifications and user confirmations
- Responsive design preserved from the original template

## Pages
- Home: /
- About: /about
- Chef: /chef
- Menu: /menu
- Reservation: /reservation
- Blog: /blog
- Blog Single: /blog-single
- Contact: /contact

## Booking Flow
POST /booking
- Fields: service_id (optional), room_id (optional), full_name, email, tel, noofv, signin, signout
- Admin notification → configured recipient list
- User confirmation → the customer’s email

### Email Templates
- Layout: resources/views/emails/layout.blade.php
- Admin: resources/views/emails/admin-booking.blade.php
- User: resources/views/emails/user-booking.blade.php

## Setup
1. Install PHP dependencies:
	- composer install
2. Copy env:
	- cp .env.example .env
3. Generate key:
	- php artisan key:generate
4. Configure mail (see below), then clear config cache.

## Mail Configuration (SMTP 465/SSL)
Use placeholders in .env (don’t commit real credentials):

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_ENCRYPTION=ssl
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD="your_password"
MAIL_FROM_ADDRESS=your_email@domain.com
MAIL_FROM_NAME="Oceanova"

## Local Testing
- Set MAIL_MAILER=log
- Submit the reservation form
- Check storage/logs/laravel.log

## Notes
- If the app is hosted with the project root as the document root, .htaccess routes traffic into public/ so assets load correctly.
