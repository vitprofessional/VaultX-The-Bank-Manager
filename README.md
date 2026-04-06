# Bank Manager

Bank Manager is a Laravel application for branch operations, including employee access control, cash tracking, account creation, debit and credit handling, reporting, and branch configuration.

## Features

- Role-based access for super admin, general admin, manager, and cashier users.
- Daily cash calculation and balance editing.
- Customer account creation, listing, viewing, and editing.
- Debit and credit entry workflows.
- Employee management and branch settings.
- Uploaded logo management for branch branding.

## Tech Stack

- Laravel 11
- PHP 8.2+
- Vite
- Bootstrap 5
- Tailwind CSS utilities for asset compilation

## Setup

1. Install dependencies.
	- `composer install`
	- `npm install`
2. Configure environment values in `.env`.
3. Generate the application key if needed.
	- `php artisan key:generate`
4. Run database migrations and seed data.
	- `php artisan migrate:fresh --seed`
5. Start the development servers.
	- `php artisan serve`
	- `npm run dev`

## Notes

- The app uses session-based role access, so ensure the employee records and roles are seeded correctly before testing the protected pages.
- Uploaded logos are stored under `public/upload/logos`.
