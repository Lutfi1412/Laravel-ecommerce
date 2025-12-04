# Laravel Project Setup Guide
Panduan ini menjelaskan cara menjalankan project Laravel secara lokal, termasuk instalasi dependency, konfigurasi environment, generate key, migrasi database, dan menjalankan server.

# Requirements
Pastikan perangkat kamu sudah terinstall:

PHP 8.1+

Composer

MySQL

Node.js + NPM (tailwind)

Laravel CLI (opsional)

# Clone Repository
``` sh
git clone https://github.com/morientes0856/ujian.git
cd your-laravel-project
```
# Install Dependencies
Install dependency backend (Laravel)
```sh
composer install
```
```sh
npm install
```
# Setup Environment .env
Copy file environment example:

```sh
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=root
DB_PASSWORD=
```
# Generate Application Key
```sh
Copy code
php artisan key:generate
```
# Migrasi Database + Seeder (Opsional)
Jalankan migrasi:
```sh
php artisan migrate
```
```sh
php artisan migrate --seed
```
# Menjalankan Backend (Laravel Server)
```sh
php artisan serve
```
# Menjalankan tailwind
```sh
npm run dev
```
