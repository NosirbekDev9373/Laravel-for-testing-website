**# Tozalash Web Sayt

 Administratorlar va kontent boshqaruvchilari uchun boshqaruv imkoniyatlari ham mavjud.

## Texnologiyalar

| Texnologiya | Vazifasi |
|---|---|
| PHP 8.3+ | Backend dasturlash tili |
| Laravel 13 | Backend framework |
| MySQL / PostgreSQL | Ma'lumotlar bazasi |
| Laravel Sanctum | Autentifikatsiya imkoniyatlari |
| Vite | Frontend assetlarini yig‘ish |
| NPM | JavaScript paketlarini boshqarish |
| PHPUnit | Avtomatlashtirilgan testlar |

## Asosiy imkoniyatlar

### 1. Umumiy sahifalar
- Bosh sahifa
- Kompaniya haqida
- Xizmatlar ro‘yxati
- Xizmat haqida batafsil ma'lumot
- Aloqa sahifasi
- Ko‘p tilli interfeys uchun tilni almashtirish

### 2. Autentifikatsiya
- Foydalanuvchini ro‘yxatdan o‘tkazish
- Tizimga kirish va chiqish
- Login ma'lumotlarini tekshirish
- Foydalanuvchi sessiyasini boshqarish

### 3. Maqolalar (Posts)
- Maqolalar ro‘yxatini ko‘rish
- Maqola haqida batafsil ma'lumot
- Maqola yaratish
- Maqolani tahrirlash
- Maqolani o‘chirish
- Rasm va galereya rasmlarini yuklash

### 4. Kontent boshqaruvi
- Kategoriyalarni boshqarish
- Teglarni boshqarish
- Maqolalarga izoh qoldirish
- Izohlarni boshqarish

### 5. Foydalanuvchi rollari va ruxsatlar

Loyihada foydalanuvchilarning rollariga qarab imkoniyatlarni cheklash uchun Laravel Policy mexanizmidan foydalaniladi.

| Rol | Imkoniyatlar |
|---|---|
| Admin | Barcha postlarni boshqarish |
| Editor | Barcha postlarni tahrirlash, ruxsat etilgan kontent amallarini bajarish |
| Blogger | Post yaratish va o‘z postlarini tahrirlash |
| User | Ochiq sahifalardan foydalanish |

Ruxsatlar `PostPolicy` orqali nazorat qilinadi.

### 6. Bildirishnomalar
- Bildirishnomalar ro‘yxatini ko‘rish
- Bitta bildirishnomani o‘qilgan deb belgilash
- Barcha bildirishnomalarni o‘qilgan deb belgilash

## Loyihani o‘rnatish

### Talablar

- PHP 8.3 yoki undan yuqori
- Composer
- Node.js va NPM
- MySQL yoki PostgreSQL
- Git


`<https://github.com/NosirbekDev9373/Laravel-for-testing-website>` 

### 2. PHP paketlarini o‘rnatish

```bash
composer install
```

### 3. Environment faylini yaratish

```bash
cp .env.example .env
```

`.env` faylida ma'lumotlar bazasi sozlamalarini kiriting:

```env
APP_NAME="Tozalash Web Sayt"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tozalash_web
DB_USERNAME=root
DB_PASSWORD=
```

Agar PostgreSQL ishlatilsa, `DB_CONNECTION`, port va boshqa DB sozlamalarini PostgreSQL parametrlariga moslang.

### 4. Application key yaratish

```bash
php artisan key:generate
```

### 5. Ma'lumotlar bazasini yaratish

Tanlangan DBMS'da `tozalash_web` nomli ma'lumotlar bazasini yarating.

Migratsiyalarni ishga tushiring:

```bash
php artisan migrate
```

### 6. Frontend paketlarini o‘rnatish

```bash
npm install
```

### 7. Loyihani ishga tushirish

Backend:

```bash
php artisan serve
```

Frontend development server:

```bash
npm run dev
```

Brauzerda quyidagi manzilni oching:

```text
http://127.0.0.1:8000
```

## Testlarni ishga tushirish

Loyihada Feature Test yordamida autentifikatsiya, CRUD amallari va ruxsatlarni tekshirish ko‘zda tutilgan.

Barcha testlarni ishga tushirish:

```bash
php artisan test
```

Faqat autentifikatsiya testlari:

```bash
php artisan test --filter=AuthTest
```

Faqat post testlari:

```bash
php artisan test --filter=PostTest
```

Alohida test metodini ishga tushirish:

```bash
php artisan test --filter=test_user_can_create_post
```

Testlar uchun alohida ma'lumotlar bazasi sozlash tavsiya etiladi. `RefreshDatabase` trait'i testlar davomida ma'lumotlar bazasini test holatiga qaytarishda ishlatiladi.

## Loyihaning papkalar tuzilishi

```text
tozalash-web-sayt/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   ├── Models/
│   └── Policies/
│
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│   └── web.php
│
├── storage/
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

## Demo kontent (namuna postlar)

Bazani toza demo kontent bilan to‘ldirish:

```bash
php artisan migrate:fresh --seed
```

Bu buyruq 14 ta kategoriya, 21 ta teg, 16 ta batafsil xizmat posti (rasm va galereya bilan) va 15 ta mijoz fikrini yaratadi. Postlarda xizmat tavsifi, so‘mdagi narx, davomiylik va bosqichma-bosqich ish jarayoni bor.

Demo foydalanuvchilar (`SEED_DEFAULT_PASSWORD` orqali parol o‘zgartiriladi, default: `Demo@12345`):

| Email | Rol |
|---|---|
| admin@tozalash.uz | admin |
| sardor@tozalash.uz | editor |
| dilnoza@tozalash.uz | blogger |
| javohir@tozalash.uz | blogger |
| aziza@tozalash.uz | user (mijoz) |

> Productionda demo foydalanuvchilar va parollarni o‘zgartiring yoki `UserSeeder`ni ishlatmang.

## Productionga chiqarish

1. `.env` faylini sozlang:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

APP_LOCALE=uz
APP_FALLBACK_LOCALE=en

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true

QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
CONTACT_EMAIL=...

# Nginx/Cloudflare orqasida bo‘lsa:
TRUSTED_PROXIES=127.0.0.1
```

2. Paketlar va kalit:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
```

3. Baza va fayl havolasi:

```bash
php artisan migrate --force
php artisan storage:link
```

4. Kesh:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

5. Frontend (agar Vite assetlari ishlatilsa):

```bash
npm ci
npm run build
```

6. Navbat (queue) ishchisi. Kontakt xabarlari va bildirishnomalar navbat orqali yuboriladi, shuning uchun worker doim ishlashi shart:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Supervisor konfiguratsiyasi:

```ini
[program:ishplatforma-worker]
command=/usr/bin/php /var/www/ishplatforma/artisan queue:work --tries=3 --timeout=90
directory=/var/www/ishplatforma
autostart=true
autorestart=true
numprocs=1
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/ishplatforma/storage/logs/worker.log
```

7. Cron (scheduler):

```cron
* * * * * cd /var/www/ishplatforma && php artisan schedule:run >> /dev/null 2>&1
```

8. HTTPS, `SESSION_SECURE_COOKIE=true` va to‘g‘ri `TRUSTED_PROXIES` bilan ishlating.

9. `.env` o‘zgarganda keshni yangilang:

```bash
php artisan optimize:clear
php artisan config:cache
```

## Xavfsizlik

- `APP_DEBUG=false` — productionda majburiy.
- `composer audit` ni muntazam yugurtiring; `composer update` bilan xavfsizlik yangilanishlarini oling.
- Demo seeder parolini (`SEED_DEFAULT_PASSWORD`) productionda o‘zgartiring.
- `storage/` va `bootstrap/cache/` papkalariga yozish huquqi faqat web server foydalanuvchisiga berilsin.
- Login, registratsiya va kontakt formasi rate-limit bilan himoyalangan; CSRF va XSS himoyasi mavjud.
- `TRUSTED_PROXIES` faqat haqiqiy proxy manzillari bilan to‘ldirilsin.
