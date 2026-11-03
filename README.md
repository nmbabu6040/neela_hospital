# Hospital CMS (Laravel 13)

A Laravel 13 starter/overlay for a responsive hospital website and session-authenticated admin CMS. Uses Blade, MySQL, Tailwind CDN and vanilla JS; no third-party Laravel packages.

## Install
1. Install PHP 8.3+, Composer, MySQL, and required PHP extensions (openssl, pdo_mysql, mbstring, tokenizer, xml, ctype, json, fileinfo).
2. `composer create-project laravel/laravel hospital-site "^13.0"`
3. Copy the contents of this archive into the fresh `hospital-site` folder, preserving paths and overwriting when prompted.
4. Create a MySQL database (e.g. `hospital_cms`) and update `.env` as shown below.
5. Run `php artisan key:generate`, `php artisan migrate --seed`, `php artisan storage:link`, `php artisan serve`.
6. Open http://127.0.0.1:8000. Admin: http://127.0.0.1:8000/admin/login

## .env values
APP_NAME="Hospital CMS"
APP_ENV=local
APP_KEY= (generate using artisan)
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital_cms
DB_USERNAME=root
DB_PASSWORD=

## Admin login
admin@example.com / password123
Change this password immediately after first login. The demo password is not safe for production.

## Content management
The CMS uses one `contents` table with a `type` field. Admin can create/edit/delete Doctors, Departments, Hospitals, Packages, News, Gallery, Offers, Videos, Journal, Sliders, Sister Concerns and other listed content types. Put additional structured values (designation, degrees, phone, chamber hours, address, facilities, test list, YouTube URL, map URL, etc.) in the `Extra data` JSON field in admin as valid JSON.

## Important production notes
- Set `APP_ENV=production`, `APP_DEBUG=false`, correct `APP_URL`, secure DB credentials and run `php artisan config:cache`, `route:cache`, `view:cache`.
- Point the domain document root to Laravel's `public/` directory. Never expose `.env` or the project root publicly.
- Ensure `storage` and `bootstrap/cache` are writable; run `php artisan storage:link`.
- Tailwind is loaded from CDN for beginner-friendly setup. For production with a strict CSP/offline assets, install/build Tailwind locally using Laravel's Vite workflow.
- This starter is a hospital website/CMS, not a clinical EMR, billing system, lab system or patient-record platform. Do not store sensitive medical records here.
