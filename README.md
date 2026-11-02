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

## Xavfsizlik

- Production muhitida `APP_DEBUG=false` bo‘lishi kerak.
