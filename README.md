<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# MindEcho: Run Guide (Windows)

This project is a Laravel + Vite application. Below are the exact steps to get it running locally on Windows using PowerShell.

## Prerequisites
- PHP 8.2+ and Composer installed
- Node.js 18+ and npm
- Herd (for serving the PHP app) turned on
- Ollama installed and available in your PATH
- SQLite is bundled (see `database/database.sqlite`)
- Bootstrap is already included via Vite (no manual setup needed)

## 1) Install dependencies
Run these commands in the project root (`C:\Users\patri\Herd\mindecho`).

```powershell
composer install
npm install
```

## 2) Environment setup
If you don't have an `.env` yet, create it from the example and generate an app key.

```powershell
if (!(Test-Path .env) -and (Test-Path .env.example)) { Copy-Item .env.example .env }
php artisan key:generate
```

Ensure your `.env` database settings match SQLite:
- `DB_CONNECTION=sqlite`
- `DB_DATABASE="database/database.sqlite"`

## 3) Run database migrations
```powershell
php artisan migrate
```

## 4) Start the queue worker
Keep a queue worker running in a separate terminal.

```powershell
php artisan queue:work
```

## 5) Start Ollama
Start the local model server (separate terminal). If Ollama isn't already running:

```powershell
ollama serve
```

## 6) Start the frontend dev server (Vite)
In another terminal, start Vite for hot-reload assets.

```powershell
npm run dev
```

## 7) Serve the PHP application (via Herd)
Turn on Herd. It will auto-detect and serve the app (no need to run `php artisan serve`).

Then visit your Herd site URL (e.g., `https://mindecho.test`).

## Typical terminal layout
- Terminal A: `php artisan queue:work`
- Terminal B: `ollama serve`
- Terminal C: `npm run dev`
- Herd app: running/serving PHP automatically

## Optional: Contact form (EmailJS)
This project’s contact form uses EmailJS on the client side.

To enable it with your own account, add these variables to `.env` and restart the Vite dev server:

```dotenv
VITE_EMAILJS_SERVICE_ID=your_service_id
VITE_EMAILJS_TEMPLATE_ID=your_template_id
VITE_EMAILJS_USER_ID=your_public_key
```

Notes:
- These are frontend (Vite) env vars; values end up in the built JS. Protect your EmailJS account by restricting allowed origins/domains.
- Keep real values out of source control; set them only in local `.env` or production environment.
- If unset, the app still runs; the contact form will show a friendly notice instead of sending.

