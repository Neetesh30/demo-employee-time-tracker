# Time Log System

A simple Laravel-based time tracking application for logging daily work entries, managing leave requests, and reviewing submitted records.

## Features

- Log multiple work tasks for a single day
- Enforce a daily maximum of 10 working hours
- Submit leave requests with validation
- Restrict task descriptions to 500 characters
- Display clear success and error messages for user actions

## Tech Stack

- Laravel 12
- PHP
- MySQL
- Vite
- Tailwind CSS

## Installation

1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install frontend dependencies:
   ```bash
   npm install
   ```
4. Configure your environment file and database settings in `.env`
5. Generate the application key:
   ```bash
   php artisan key:generate
   ```
6. Run database migrations:
   ```bash
   php artisan migrate
   ```
7. Seed the database with sample data and a demo user:
   ```bash
   php artisan db:seed
   ```

## Running the Application

Start the Laravel server:
```bash
php artisan serve
```

Start the frontend build watcher:
```bash
npm run dev
```

## Demo Account

A seeded test account is available for quick access:

- Email: `test@example.com`
- Password: `password`

## Testing

Run the test suite with:
```bash
php artisan test
```

## Notes

- The app uses authentication and verified email flow from Laravel Breeze-style scaffolding.
- The main user-facing pages are time logs and leave management.
