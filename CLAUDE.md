# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel 12 application (Cronos TDR) that appears to be built for sensor data management. The project includes:

- **SensorData Model**: Handles sensor data with fields for temperature, humidity, location, and timestamps
- **Database**: Uses SQLite by default (configured in .env.example)
- **Frontend**: Uses Vite with Laravel Mix, includes Tailwind CSS v4
- **Testing**: PHPUnit configured for both Feature and Unit tests

## Development Commands

### Starting Development Environment
```bash
# Start the full development stack (server, queue, logs, vite)
composer run dev

# Or start individual services:
php artisan serve          # Development server
npm run dev               # Vite development server
php artisan queue:listen   # Queue worker
php artisan pail          # Log viewer
```

### Testing
```bash
# Run all tests
composer run test
# Or manually:
php artisan test

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Database Operations
```bash
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Rollback migrations
php artisan db:seed             # Run database seeders
php artisan migrate:fresh --seed # Fresh migration with seeding
```

### Laravel Pint (Code Formatting)
```bash
./vendor/bin/pint           # Format all files
./vendor/bin/pint --test    # Check formatting without changes
```

### Asset Building
```bash
npm run build              # Build for production
npm run dev               # Development build with watching
```

## Architecture Notes

### Models
- **SensorData** (`app/Models/SensorData.php`): Core model for sensor data management
  - No Laravel timestamps (timestamps = false)
  - Fillable fields: topic, sensor_type, temperatura, humitat, location, timestamp
  - Decimal casting for temperature and humidity values

### Database Configuration
- Default database: SQLite (database/database.sqlite)
- Alternative databases can be configured via environment variables
- Uses database for sessions, cache, and queues by default

### Frontend Stack
- **Vite**: Module bundler with Laravel plugin
- **Tailwind CSS v4**: Styling framework via @tailwindcss/vite plugin
- Entry points: `resources/css/app.css` and `resources/js/app.js`

### Environment Setup
- Copy `.env.example` to `.env`
- SQLite database file is auto-created on first migration
- No additional environment setup required for basic development

## File Structure
- `app/Models/` - Eloquent models
- `routes/web.php` - Web routes (currently minimal)
- `database/migrations/` - Database schema migrations
- `tests/Feature/` - Feature tests
- `tests/Unit/` - Unit tests
- `resources/` - Frontend assets (CSS, JS, views)