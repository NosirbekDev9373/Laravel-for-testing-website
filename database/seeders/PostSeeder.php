<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@tozalash.uz')->firstOrFail();
        $editor = User::where('email', 'sardor@tozalash.uz')->firstOrFail();
        $blogger = User::where('email', 'dilnoza@tozalash.uz')->firstOrFail();
        $blogger2 = User::where('email', 'javohir@tozalash.uz')->firstOrFail();

        $posts = [
            [
                'title' => 'Kvartirani 4 soatda mukammal tozalash: chang, dog‘ va hidlardan xalos bo‘ling',
                'category' => 'Uy tozalash',
                'author' => $blogger,
                'short_content' => 'Ishdan charchab qaytdingizmi? Jamoa uyingizni mehmon qabul qilishga tayyor holatga keltiradi — ekologik vositalar va 24 soatlik kafolat bilan.',
                'content' => <<<'TEXT'
Kvartira yoki uyni tozalash har kuni vaqt va kuch talab qiladi. Ayniqsa ishdan charchab qaytganingizda yoki uyda bolalar va uy hayvonlari bo‘lsa, chuqur tozalashga vaqt topish qiyin. Bizning jamoa 4 soat ichida uyingizni mehmonlarni qabul qilishga tayyor holatga keltiradi.

Xizmat nimani o‘z ichiga oladi?
- Barcha xonalarda chang yig‘ish va yuza tozalash
- Polni yuvish va dezinfeksiya qilish
- Oshxonada plita, mebel va texnika yuzalarini tozalash
- Hammom va sanuzelni yuvish, dezinfeksiya qilish
- Deraza va oynalarni ichkaridan yuvish
- Chiqindilarni olib chiqish

Ish jarayoni:
1. Baholash. Buyurtmadan oldin xonadon maydoni va ifloslanish darajasini aniqlaymiz. Bu narx va vaqtni aniq belgilashga yordam beradi.
2. Tayyorlash. Kerakli jihoz va ekologik tozalash vositalarini olib kelamiz. Qimmatbaho buyumlarni ehtiyotkorlik bilan joylashtiramiz.
3. Tozalash. Har bir xona alohida ketma-ketlikda tozalanadi: yuqoridan pastga, quruqdan ho‘lga.
4. Nazorat. Ish tugagach, har bir xonani tekshiramiz va kamchiliklarni darhol bartaraf qilamiz.
5. Topshirish. Sizdan xizmat sifatini qabul qilib olamiz va 24 soatlik kafolat beramiz.

Narx nimaga bog‘liq?
- Xonadon maydoni
- Tozalash turi (qo‘llab-quvvatlash yoki chuqur)
- Qo‘shimcha xizmatlar (deraza, gilam, mebel)

Nega aynan biz?
- Ekologik sertifikatlangan vositalar
- O‘qitilgan va sug‘urtalangan jamoa
- Belgilangan vaqtda yetib kelish
- 24 soatlik sifat kafolati

Buyurtma berish uchun Kontakt sahifasidagi forma orqali yozing yoki telefon orqali bog‘laning. Mutaxassis 15 daqiqa ichida aniq narxni aytadi.
TEXT,
                'price' => 350000,
                'duration' => 4,
                'image' => 'high-angle-woman-cleaning-floors.jpg',
                'image_hover' => 'services/young-smiling-woman-wearing-rubber-gloves-cleaning-stove.jpg',
                'gallery' => [
                    'services/woman-holding-rag-detergent-cleaning-cooker.jpg',
                    'high-angle-woman-cleaning-floors.jpg',
                ],
                'tags' => ['Uy', 'Ekologik', 'Kafolat'],
            ],
            [
                'title' => 'Ofis tozalash: xodimlar kelishidan oldin toza va sog‘lom muhit',
                'category' => 'Ofis tozalash',
                'author' => $editor,
                'short_content' => 'Ofisingiz obro‘yingizning bir qismi. Har kuni yoki haftada bir marta professional tozalash bilan mijozlarga yoqimli taassurot qoldiring.',
                'content' => <<<'TEXT'
Ofis — bu nafaqat ish joyi, balki mijozlar va hamkorlar sizni birinchi marta ko‘radigan maskan. Changli stol, iflos oyna va yoqimsiz hid butun jamoa haqidagi taassurotni buzadi. Biz ofisingizni xodimlar kelishidan oldin toza va sog‘lom muhitga aylantiramiz.

Xizmat tarkibi:
- Ish stollari, kompyuter va telefonlarni yumshoq tozalash
- Polni yuvish va gilamni changyutish
- Oshxona va dam olish xonasini tozalash
- Sanuzel va hammomni dezinfeksiya qilish
- Deraza va shisha yuzalarni yuvish
- Chiqindi chelaklarini bo‘shatish va yuvish

Ish jarayoni:
1. Kelishuv. Ofis maydoni, xonalar soni va ish vaqtini kelishamiz.
2. Reja. Tozalashni ish grafigingizga moslaymiz — erta tong, kechqurun yoki dam olish kunlari.
3. Bajarish. Jamoa belgilangan vaqtda kelib, reja bo‘yicha ishlaydi.
4. Nazorat. Har bir zonani tekshirib, hisobot beramiz.
5. Doimiy xizmat. Haftada bir yoki har kuni tozalash variantini tanlashingiz mumkin.

Narx nimaga bog‘liq?
- Ofis maydoni va xonalar soni
- Tozalash chastotasi
- Qo‘shimcha xizmatlar (deraza, gilam, dezinfeksiya)

Afzalliklari:
- Xodimlar kasalligi kamayadi
- Mijozlarda ishonchli taassurot
- Vaqtingizni asosiy ishga sarflaysiz
- Shartnoma va hisob-faktura bilan rasmiy xizmat

Kontakt formasi orqali yozing — ofisingiz uchun individual taklif tayyorlaymiz.
TEXT,
                'price' => 550000,
                'duration' => 3,
                'image' => 'services/people-taking-care-office-cleaning.jpg',
                'image_hover' => 'services/person-taking-care-office.jpg',
                'gallery' => [
                    'services/people-taking-care-office-cleaning.jpg',
                    'services/person-taking-care-office.jpg',
                ],
                'tags' => ['Ofis', 'Professional', 'Tez xizmat'],
            ],
            [
                'title' => 'Gilam va yumshoq mebelni chuqur yuvish: 24 soatda quritamiz',
                'category' => 'Gilam va mebel yuvish',
                'author' => $blogger,
                'short_content' => 'Gilam va divanlaringizdagi chang, allergen va dog‘larni professional uskuna bilan olib tashlaymiz. Quritish muddati — 24 soat.',
                'content' => <<<'TEXT'
Gilam va yumshoq mebel uyning eng ko‘p chang yig‘adigan joylaridan biri. Changyutgich yuzadagi changni oladi, lekin tolalar ichidagi allergen, oziq-ovqat dog‘lari va yoqimsiz hidlar qolib ketadi. Professional yuvish esa gilamning umrini uzaytiradi va uyga toza havo olib keladi.

Qanday ishlaymiz?
- Gilam va mebel turini aniqlaymiz
- Dog‘ turiga mos kimyoviy vosita tanlaymiz
- Chuqur yuvish ekstraktori bilan tolalarni yuvamiz
- Suvni to‘liq so‘rib olamiz
- Quritish uchun ventilyator qo‘yamiz

Ish jarayoni:
1. Diagnostika. Gilam tolasini va dog‘ turini tekshiramiz.
2. Sinov. Kichik ko‘rinmas joyda vositani sinab ko‘ramiz.
3. Yuvish. Issiq suv va professional uskuna bilan chuqur yuvamiz.
4. Quritish. 24 soat ichida to‘liq quriydi.
5. Tekshiruv. Natijani birga ko‘rib chiqamiz.

Nimaga e’tibor berish kerak?
- Arzon “quruq tozalash” ko‘pincha dog‘ni chuqurroq singdiradi
- Noto‘g‘ri vosita gilam rangini buzishi mumkin
- To‘liq quritilmagan gilamda zamburug‘ paydo bo‘ladi

Kafolat: agar dog‘ qaytsa yoki natija sizni qoniqtirmasa, 3 kun ichida bepul qayta ishlaymiz.

Gilam, divan, kreslo va matraslarni yuvamiz. Aniq narxni rasm va o‘lcham bo‘yicha aytamiz.
TEXT,
                'price' => 250000,
                'duration' => 2,
                'image' => 'services/close-up-mop-cleaning-industrial-plant-floor.jpg',
                'image_hover' => 'services/woman-holding-rag-detergent-cleaning-cooker.jpg',
                'gallery' => [
                    'services/woman-holding-rag-detergent-cleaning-cooker.jpg',
                    'high-angle-woman-cleaning-floors.jpg',
                ],
                'tags' => ['Gilam', 'Uy', 'Kafolat'],
            ],
            [
                'title' => 'Deraza va oynalarni iz qoldirmasdan yuvish: yorug‘lik 2 barobar ko‘proq',
                'category' => 'Deraza va fasad yuvish',
                'author' => $blogger2,
                'short_content' => 'Changli oynalar quyosh nurini ushlab qoladi. Professional yuvish bilan uyingiz yorqinroq, ko‘rinishi esa ancha toza bo‘ladi.',
                'content' => <<<'TEXT'
Deraza va oynalar uyning “ko‘zi”. Ular chang, yomg‘ir izlari va qush izlari bilan qoplanib, quyosh nurini kamaytiradi. Toza oynalar xonani yorqinroq qiladi, elektr energiyasini tejaydi va uyning tashqi ko‘rinishini butunlay o‘zgartiradi.

Xizmat tarkibi:
- Deraza oynalari va ramkalarini yuvish
- Oynalar orasidagi changni tozalash
- Deraza tokchalari va podokonniklarni tozalash
- Balkon va lodjiya oynalarini yuvish
- Ofis va do‘kon vitrinalarini yuvish
- Ko‘p qavatli binolarda xavfsiz uskunalar bilan ishlash

Ish jarayoni:
1. Baholash. Derazalar soni, balandligi va ifloslanish darajasini aniqlaymiz.
2. Xavfsizlik. Balandlikda ishlash uchun maxsus jihoz va sug‘urta.
3. Yuvish. Ramka, shisha va tokchalarni ketma-ket tozalaymiz.
4. Sayqal. Shishani iz qoldirmaydigan vosita bilan sayqallaymiz.
5. Tekshiruv. Har bir oynani yorug‘likda tekshiramiz.

Narx nimaga bog‘liq?
- Derazalar soni va o‘lchami
- Balandlik va kirish qulayligi
- Ichki yoki tashqi tomon

Maslahat: derazalarni yiliga kamida 2 marta professional yuvish tavsiya etiladi. Bu ramka va germetik qatlamning umrini uzaytiradi.

Kontakt orqali derazalar sonini yozing — 15 daqiqada taxminiy narxni aytamiz.
TEXT,
                'price' => 300000,
                'duration' => 2,
                'image' => 'slideshow/afro-woman-cleaning-window-with-rag-home.jpg',
                'image_hover' => 'slideshow/unrecognizable-cleaner-walking-into-hotel-room-with-tools-detergents.jpg',
                'gallery' => [
                    'slideshow/afro-woman-holding-bucket-with-cleaning-items.jpg',
                    'slideshow/afro-woman-cleaning-window-with-rag-home.jpg',
                ],
                'tags' => ['Deraza', 'Uy', 'Professional'],
            ],
            [
                'title' => 'Dezinfeksiya xizmati: virus va bakteriyalardan 99,9% himoya',
                'category' => 'Dezinfeksiya',
                'author' => $editor,
                'short_content' => 'Bolalar bog‘chasi, ofis yoki uy — yuqumli kasalliklar mavsumida professional dezinfeksiya eng ishonchli chora.',
                'content' => <<<'TEXT'
Dezinfeksiya — bu oddiy tozalashdan farqli ravishda mikroorganizmlarni yo‘q qilishga qaratilgan chora. Gripp, virusli infeksiyalar mavsumida, kasalxona yoki bolalar muassasalarida, shuningdek uy hayvonlari bo‘lgan xonadonlarda bu xizmat ayniqsa muhim.

Qayerda qo‘llaniladi?
- Kvartira va xususiy uylar
- Ofislar va coworking markazlari
- Bolalar bog‘chalari va maktablar
- Kafe, restoran va do‘konlar
- Fitnes zallari va beauty salonlar
- Avtomobil salonlari

Ish jarayoni:
1. Baholash. Xona maydoni va yuqori teginish nuqtalarini aniqlaymiz.
2. Tayyorlash. Oziq-ovqat va shaxsiy buyumlarni himoyalaymiz.
3. Ishlov berish. Sertifikatlangan vosita bilan purkash yoki ULV usulida ishlaymiz.
4. Ta’sir vaqti. Vosita ko‘rsatmasiga ko‘ra xonani yopiq holda qoldiramiz.
5. Shamollatish. Belgilangan vaqtdan keyin xonani shamollatamiz.

Narx nimaga bog‘liq?
- Xona maydoni
- Dezinfeksiya turi (profilaktik yoki o‘choqli)
- Qayta ishlash chastotasi

Muhim: ishlatiladigan barcha vositalar sertifikatlangan va inson salomatligi uchun xavfsiz. Xizmat yakunida ishlov berilganligi to‘g‘risida hujjat beramiz.

Buyurtma uchun xona maydonini va maqsadni yozing — mos yechimni taklif qilamiz.
TEXT,
                'price' => 450000,
                'duration' => 2,
                'image' => 'coronavirus-pandemic-disinfector-protective-suit-mask-sprays-disinfectants-house-office.jpg',
                'image_hover' => 'disinfecting-home.jpg',
                'gallery' => [
                    'essential-goods-during-epidemic-prevention-protection.jpg',
                    'disinfecting-home.jpg',
                ],
                'tags' => ['Dezinfeksiya', 'Ofis', 'Kafolat'],
            ],
            [
                'title' => 'Remontdan keyingi tozalash: qurilish changi va bo‘yoq izlarini olib tashlaymiz',
                'category' => 'Remontdan keyingi tozalash',
                'author' => $blogger2,
                'short_content' => 'Remont tugadi, lekin uy hali tayyor emasmi? Maxsus jihozlar bilan qurilish changini to‘liq tozalaymiz.',
                'content' => <<<'TEXT'
Remont tugadi, lekin uyga kirish hali erta? Qurilish changi, bo‘yoq tomchilari, elim izlari va gips qoldiqlari oddiy tozalash bilan ketmaydi. Noto‘g‘ri tozalash esa yangi yuzalarni shikastlashi mumkin. Biz remontdan keyingi tozalashni bosqichma-bosqich, xavfsiz bajarishamiz.

Xizmat tarkibi:
- Qurilish changini HEPA filtrli changyutgich bilan yig‘ish
- Pol, devor va shiftdagi bo‘yoq va gips izlarini tozalash
- Deraza va ramkalardagi himoya plyonkalarini olib tashlash
- Santexnika va kafel yuzalarini tozalash
- Mebel va jihozlarni changdan tozalash
- Hidni yo‘qotish va xonani shamollatish

Ish jarayoni:
1. Baholash. Remont turi va ifloslanish hajmini aniqlaymiz.
2. Quruq tozalash. Avval changni yig‘amiz — ho‘l tozalashdan oldin.
3. Dog‘larni olib tashlash. Bo‘yoq, elim va gips uchun maxsus vositalar.
4. Ho‘l tozalash. Pol va yuzalarni yuvamiz.
5. Yakuniy nazorat. Har bir xonani yorug‘likda tekshiramiz.

Narx nimaga bog‘liq?
- Kvadrat metr
- Remont turi (kosmetik yoki kapital)
- Qo‘shimcha xizmatlar (deraza, ventilyatsiya)

Maslahat: remontdan keyin tozalashni mebel joylashdan oldin qilish eng qulay. Shunda chang barcha burchaklardan to‘liq chiqadi.

Kontakt formasi orqali xonadon maydonini yozing — aniq taklif tayyorlaymiz.
TEXT,
                'price' => 700000,
                'duration' => 6,
                'image' => 'services/professional-industrial-cleaner-protective-uniform-cleaning-floor-food-processing-plant.jpg',
                'image_hover' => 'services/close-up-mop-cleaning-industrial-plant-floor.jpg',
                'gallery' => [
                    'high-angle-woman-cleaning-floors.jpg',
                    'services/professional-industrial-cleaner-protective-uniform-cleaning-floor-food-processing-plant.jpg',
                ],
                'tags' => ['Remont', 'Uy', 'Professional'],
            ],
            [
                'title' => 'Avtomobil salonini kimyoviy tozalash: yangi mashina effekti',
                'category' => 'Avtomobil xizmatlari',
                'author' => $blogger2,
                'short_content' => 'Salondagi dog‘, hid va changlarni professional kimyoviy tozalash bilan olib tashlaymiz. Natija — yangi avtomobil saloni.',
                'content' => <<<'TEXT'
Avtomobil saloni — siz eng ko‘p vaqt o‘tkazadigan joylardan biri. Chang, oziq-ovqat dog‘lari, uy hayvonlari juni va yoqimsiz hidlar vaqt o‘tishi bilan to‘planadi. Professional kimyoviy tozalash salonni yangi holatga qaytaradi va avtomobil qiymatini saqlaydi.

Xizmat tarkibi:
- Salonni chuqur changyutgichlash
- O‘rindiq va gilamlarga kimyoviy ishlov berish
- Panel, eshik va plastik yuzalarni tozalash
- Konditsioner va ventilyatsiya kanallarini tozalash
- Hiddan xalos qilish
- Charm va mato yuzalarni himoyalash

Ish jarayoni:
1. Baholash. Salon holati va dog‘ turlarini aniqlaymiz.
2. Quruq tozalash. Barcha chang va mayda qoldiqlarni yig‘amiz.
3. Kimyoviy tozalash. O‘rindiq va gilamlarga mos vosita bilan ishlaymiz.
4. Ekstraksiya. Kir va suvni to‘liq so‘rib olamiz.
5. Quritish va sayqal. Salonni quritib, plastik yuzalarni sayqallaymiz.

Narx nimaga bog‘liq?
- Avtomobil turi va salon hajmi
- Ifloslanish darajasi
- Qo‘shimcha xizmatlar (charm, konditsioner)

Natija: 3–4 soat ichida salon yangi avtomobildagidek ko‘rinadi. Kuchli dog‘lar uchun qo‘shimcha ishlov kerak bo‘lishi mumkin.

Buyurtma uchun avtomobil rusumi va salon holatini yozing.
TEXT,
                'price' => 400000,
                'duration' => 3,
                'image' => 'services/man-polishing-car-inside-car-service.jpg',
                'image_hover' => 'services/man-polishing-car-inside.jpg',
                'gallery' => [
                    'services/man-polishing-car-inside.jpg',
                    'services/man-polishing-car-inside-car-service.jpg',
                ],
                'tags' => ['Avto', 'Professional', 'Kafolat'],
            ],
            [
                'title' => 'Kafe va restoranlar uchun sanitariya tozalash: mijozlar ishonchi uchun',
                'category' => 'Kafe va restoran xizmatlari',
                'author' => $editor,
                'short_content' => 'Oshxona va zal gigienasi — biznesingiz obro‘si. HACCP talablariga mos tozalash xizmati.',
                'content' => <<<'TEXT'
Kafe yoki restoran uchun gigiena — bu shunchaki tozalik emas, balki biznes obro‘si va mijoz ishonchi. Oshxona yuzalari, stol va stullar, hammom va ventilyatsiya toza bo‘lsa, mijoz qaytib keladi. Biz HoReCa sohasiga mos, tez va sezilmas tozalash xizmatini taklif qilamiz.

Xizmat tarkibi:
- Oshxona yuzalari va jihozlarni yog‘dan tozalash
- Zal, stol va stullarni dezinfeksiya qilish
- Hammomlarni chuqur tozalash
- Pol va gilamlarni yuvish
- Ventilyatsiya panjaralarini tozalash
- Chiqindi zonalarini yuvish va hidni yo‘qotish

Ish jarayoni:
1. Audit. Muassasa maydoni va ish grafigini o‘rganamiz.
2. Reja. Ishni mijozlar kam bo‘lgan vaqtga moslaymiz.
3. Tozalash. Oshxona va zalni alohida zonalarda ishlaymiz.
4. Dezinfeksiya. Yuqori teginish nuqtalariga alohida e’tibor.
5. Hisobot. Bajarilgan ishlar bo‘yicha hisobot beramiz.

Narx nimaga bog‘liq?
- Muassasa maydoni
- Tozalash chastotasi (kunlik, haftalik)
- Qo‘shimcha xizmatlar (ventilyatsiya, gilam)

Nega aynan biz?
- Oziq-ovqat bilan ishlash qoidalarini bilamiz
- Sertifikatlangan va xavfsiz vositalar
- Ish jarayonini to‘xtatmaymiz
- Shartnoma asosida doimiy xizmat

Kontakt orqali muassasa turi va maydonini yozing — individual taklif tayyorlaymiz.
TEXT,
                'price' => 800000,
                'duration' => 4,
                'image' => 'coffe-shop-with-wooden-walls-unfocused.jpg',
                'image_hover' => 'services/people-taking-care-office-cleaning.jpg',
                'gallery' => [
                    'services/professional-industrial-cleaner-protective-uniform-cleaning-floor-food-processing-plant.jpg',
                    'coffe-shop-with-wooden-walls-unfocused.jpg',
                ],
                'tags' => ['HoReCa', 'Ofis', 'Professional'],
            ],
            [
                'title' => 'Kichik biznes uchun brending: birinchi taassurotni professional qiling',
                'category' => 'Brending va dizayn',
                'author' => $admin,
                'short_content' => 'Logo, vizitka va ijtimoiy tarmoq dizayni — mijoz sizni birinchi 7 soniyada baholaydi. Brending bilan ishonch uyg‘oting.',
                'content' => <<<'TEXT'
Birinchi taassurot 7 soniyada shakllanadi. Logo, vizitka, menyu yoki ijtimoiy tarmoqdagi sahifa — bularning barchasi mijozning sizga bo‘lgan ishonchini belgilaydi. Kichik biznes uchun professional brending qimmat emas, lekin juda katta farq qiladi.

Xizmat tarkibi:
- Logo va brend identifikatsiyasi
- Vizitka va blank dizayni
- Ijtimoiy tarmoqlar uchun shablonlar
- Menyu, flayer va bannerlar
- Brend ranglari va shrift tizimi
- Tayyor fayllarni turli formatlarda topshirish

Ish jarayoni:
1. Brif. Biznesingiz, mijozlaringiz va maqsadlaringizni o‘rganamiz.
2. Tadqiqot. Raqobatchilar va soha vizual tilini tahlil qilamiz.
3. Konsepsiya. 2–3 xil yo‘nalishda eskizlar tayyorlaymiz.
4. Tanlov. Siz bilan birga eng mos variantni tanlaymiz.
5. Yakun. Barcha fayllarni tayyor holatda topshiramiz.

Narx nimaga bog‘liq?
- Xizmat hajmi (faqat logo yoki to‘liq brending)
- Dizayn murakkabligi
- Tayyorlash muddati

Nega bu muhim?
- Professional ko‘rinish ishonch oshiradi
- Brend eslab qolinadi
- Raqobatchilardan ajralib turasiz
- Sifatli materiallar bilan uzoq xizmat qiladi

Kontakt orqali biznesingiz haqida qisqacha yozing — bepul maslahat va taxminiy narxni aytamiz.
TEXT,
                'price' => 600000,
                'duration' => 5,
                'image' => 'cleaning-concept-with-man-showing-business-card.jpg',
                'image_hover' => 'male-wearing-apron-female-white-t-shirt-smiling-broadly-being-glad-clean.png',
                'gallery' => [
                    'teams/young-cleaning-man-wearing-casual-clothes.jpg',
                    'teams/happy-young-woman-wiping-kitchen-counter-wearing-yellow-gloves.jpg',
                ],
                'tags' => ['Brending', 'Byudjet', 'Tez xizmat'],
            ],
            [
                'title' => 'Oshxona va maishiy texnikani chuqur tozalash: yog‘ va kuyindilardan xalos bo‘ling',
                'category' => 'Uy tozalash',
                'author' => $blogger,
                'short_content' => 'Plita, pech va kaputdagi yog‘ qatlami faqat ko‘rinishni emas, havoni ham buzadi. Maxsus vositalar bilan chuqur tozalaymiz.',
                'content' => <<<'TEXT'
Oshxona — uyning yuragi, lekin ayni paytda eng ko‘p yog‘ va hid to‘planadigan joy. Plita, pech, kaput va mebel yuzalaridagi yog‘ qatlami vaqt o‘tishi bilan qotib qoladi va oddiy vositalar bilan ketmaydi. Maxsus vositalar bilan chuqur tozalash oshxonani yangilaydi.

Xizmat tarkibi:
- Plita va pechni yog‘dan tozalash
- Kaput va filtrlarini yuvish
- Oshxona mebellari va fasadlarini tozalash
- Mikroto‘lqinli pech va muzlatgichni ichidan tozalash
- Kafel va choklarni yuvish
- Hidni yo‘qotish va dezinfeksiya

Ish jarayoni:
1. Baholash. Yog‘ qatlami va ifloslanish darajasini aniqlaymiz.
2. Himoya. Oziq-ovqat va idishlarni himoyalaymiz.
3. Ishlov. Yuzalarga mos yog‘ yechuvchi vosita qo‘llaymiz.
4. Yuvish. Toza suv bilan yuvib, quritamiz.
5. Sayqal. Kafel va metal yuzalarni sayqallaymiz.

Narx nimaga bog‘liq?
- Oshxona hajmi
- Yog‘ qatlami darajasi
- Qo‘shimcha jihozlar (kaput, pech)

Maslahat: kaput filtrlarini oyiga bir marta tozalash yong‘in xavfini kamaytiradi va havo sifatini yaxshilaydi.

Buyurtma uchun oshxona rasmini yuboring — aniq narxni aytamiz.
TEXT,
                'price' => 200000,
                'duration' => 2,
                'image' => 'services/young-smiling-woman-wearing-rubber-gloves-cleaning-stove.jpg',
                'image_hover' => 'services/woman-holding-rag-detergent-cleaning-cooker.jpg',
                'gallery' => [
                    'services/young-smiling-woman-wearing-rubber-gloves-cleaning-stove.jpg',
                    'services/woman-holding-rag-detergent-cleaning-cooker.jpg',
                ],
                'tags' => ['Uy', 'Ekologik', 'Byudjet'],
            ],
            [
                'title' => 'Uy va ofis remonti: smetadan topshirishgacha shaffof jarayon',
                'category' => 'Ta’mirlash va remont',
                'author' => $blogger2,
                'short_content' => 'Devor, pol va sanitariya ishlarini bosqichma-bosqich bajarib, har qadamda hisobot beramiz. Narx oldidan aniq smeta tuzamiz.',
                'content' => <<<'TEXT'
Remont — bu har bir detal muhim bo‘lgan jarayon. Ko‘pincha muammo ish boshlanishidan oldin narx va muddat kelishilmaganligida yuzaga keladi. Biz avval aniq smeta tuzamiz, keyin esa belgilangan reja bo‘yicha ishlaymiz.

Nima qilamiz?
- Devor va shiftlarni tekislash, bo‘yash
- Pol yotqizish va plitka ishlari
- Sanitariya va elektr ishlarini tartibga solish
- Eshik va deraza o‘rnatish
- Yakuniy tozalash va topshirish

Ish jarayoni:
1. Ko‘zdan kechirish. Hamma ish hajmini o‘lchab, texnik topshiriq tuzamiz.
2. Smeta. Material va ish haqi alohida ko‘rsatiladi — yashirin to‘lovlar yo‘q.
3. Tayyorgarlik. Joyni tozalaymiz, materiallarni olib kelamiz.
4. Bajarish. Har hafta bajarilgan ishlar bo‘yicha hisobot beramiz.
5. Topshirish. Kamchiliklarni bartaraf etib, kalitlarni topshiramiz.

Narx nimaga bog‘liq?
- Maydon va ish turi
- Materiallar sifati
- Muddat va murakkablik

Kafolat: bajarilgan ishlarga 12 oy kafolat beramiz.

Kontakt formasi orqali maydon va remont turini yozing — 2 kun ichida smeta tayyorlaymiz.
TEXT,
                'price' => 12000000,
                'duration' => 72,
                'image' => 'male-wearing-apron-female-white-t-shirt-smiling-broadly-being-glad-clean.png',
                'image_hover' => 'services/close-up-mop-cleaning-industrial-plant-floor.jpg',
                'gallery' => [
                    'services/professional-industrial-cleaner-protective-uniform-cleaning-floor-food-processing-plant.jpg',
                    'high-angle-woman-cleaning-floors.jpg',
                ],
                'tags' => ['Ta’mirlash', 'Professional', 'Kafolat'],
            ],
            [
                'title' => 'Veb-sayt va raqamli platforma yaratish: biznesingizni onlaynga olib chiqamiz',
                'category' => 'IT va raqamli xizmatlar',
                'author' => $admin,
                'short_content' => 'Zamonaviy, tez va telefonga mos veb-saytlar. Dizayndan ishga tushirishgacha to‘liq xizmat.',
                'content' => <<<'TEXT'
Veb-sayt — biznesingizning 24 soat ishlaydigan vizitkasi. Eski yoki sekin sayt mijozni birinchi soniyada yo‘qotadi. Biz zamonaviy, tez va telefonga mos veb-saytlar yaratamiz.

Xizmat tarkibi:
- Biznes tahlili va sayt tuzilmasi
- Zamonaviy dizayn va adaptsiv layout
- Dasturlash va ma’muriy panel
- SEO uchun texnik tayyorgarlik
- Testlash va ishga tushirish
- 1 oy bepul texnik qo‘llab-quvvatlash

Ish jarayoni:
1. Brif. Maqsad va mijozlaringizni o‘rganamiz.
2. Prototip. Sayt tuzilmasini tasdiqlaymiz.
3. Dizayn. Vizual uslubni tayyorlaymiz.
4. Dasturlash. Saytni yozamiz va kontent bilan to‘ldiramiz.
5. Ishga tushirish. Domen va hosting sozlamalarini tartibga solamiz.

Narx nimaga bog‘liq?
- Sahifalar soni va funksionallik
- Dizayn murakkabligi
- Katalog, to‘lov yoki boshqa integratsiyalar

Natija: sayt 1–3 hafta ichida ishga tushadi. Barcha fayllar va kodlar sizga topshiriladi.

Kontakt orqali biznesingiz haqida yozing — bepul texnik topshiriq tayyorlaymiz.
TEXT,
                'price' => 8500000,
                'duration' => 80,
                'image' => 'cleaning-concept-with-man-showing-business-card.jpg',
                'image_hover' => 'coffe-shop-with-wooden-walls-unfocused.jpg',
                'gallery' => [
                    'teams/young-cleaning-man-wearing-casual-clothes.jpg',
                    'cleaning-concept-with-man-showing-business-card.jpg',
                ],
                'tags' => ['IT', 'Professional', 'Kafolat'],
            ],
            [
                'title' => 'Go‘zallik va parvarish xizmatlari: o‘zingizga vaqt ajrating',
                'category' => 'Go‘zallik va sog‘lomlashtirish',
                'author' => $blogger,
                'short_content' => 'Professional kosmetolog va stilistlar bilan teri, soch va umumiy holatingizni yaxshilang.',
                'content' => <<<'TEXT'
O‘zingizga g‘amxo‘rlik qilish — bu hashamat emas, balki sog‘lom turmushning bir qismi. Professional kosmetolog va stilistlar yordamida teri, soch va umumiy holatingizni yaxshilang.

Xizmat tarkibi:
- Teri tozalash va namlash muolajalari
- Soch kesish, bo‘yash va parvarish
- Manikyur va pedikyur
- Massaj va sog‘lomlashtirish
- Individual maslahat va uy parvarishi rejasi

Ish jarayoni:
1. Konsultatsiya. Teri va soch holatini baholaymiz.
2. Dastur. Sizga mos muolajalar ro‘yxatini tuzamiz.
3. Bajarish. Sertifikatlangan vositalar bilan ishlaymiz.
4. Nazorat. Natijani baholab, keyingi qadamlarni belgilaymiz.
5. Uy parvarishi. Uyda qiladigan oddiy qoidalarni beramiz.

Narx nimaga bog‘liq?
- Muolaja turi va davomiyligi
- Mutaxassis malakasi
- Vositalar sifati

Gigiena: barcha asboblar har bir mijozdan keyin dezinfeksiya qilinadi.

Kontakt orqali qiziqkan xizmatingizni yozing — vaqt va narxni kelishamiz.
TEXT,
                'price' => 350000,
                'duration' => 2,
                'image' => 'teams/happy-young-woman-wiping-kitchen-counter-wearing-yellow-gloves.jpg',
                'image_hover' => 'avatar/happy-customer-01.jpg',
                'gallery' => [
                    'avatar/happy-customer-03.jpg',
                    'male-wearing-apron-female-white-t-shirt-smiling-broadly-being-glad-clean.png',
                ],
                'tags' => ['Go‘zallik', 'Professional', 'Byudjet'],
            ],
            [
                'title' => 'Individual darslar va konsalting: maqsadingizga tezroq yeting',
                'category' => 'Ta’lim va konsalting',
                'author' => $editor,
                'short_content' => 'Tajribali mutaxassislar bilan shaxsiy darslar, biznes va karyera bo‘yicha maslahatlar.',
                'content' => <<<'TEXT'
Yangi ko‘nikma o‘rganish yoki biznesingizni rivojlantirish uchun individual yondashuv kerak. Tajribali o‘qituvchilar va maslahatchilar bilan maqsadingizga tezroq yetasiz.

Xizmat tarkibi:
- Individual onlayn va oflayn darslar
- Dasturlash, dizayn va raqamli ko‘nikmalar
- Biznes-reja va marketing bo‘yicha konsalting
- Karyera va suhbatga tayyorgarlik
- Haftalik nazorat va uy vazifalari

Ish jarayoni:
1. Maqsad. Nima o‘rganmoqchi ekanligingizni aniqlaymiz.
2. Sinov. Hozirgi darajangizni baholaymiz.
3. Reja. Haftalik darslar jadvalini tuzamiz.
4. Dars. Amaliy mashg‘ulotlar va misollar.
5. Natija. Har oy bilimingizni tekshirib, rejani yangilaymiz.

Narx nimaga bog‘liq?
- Dars soni va davomiyligi
- Mutaxassis tajribasi
- Individual yoki guruhli format

Bepul sinov darsi: birinchi 30 daqiqa bepul.

Kontakt orqali qiziqishlaringizni yozing — o‘qituvchi bilan tanishtiramiz.
TEXT,
                'price' => 450000,
                'duration' => 3,
                'image' => 'cleaning-concept-with-man-showing-business-card.jpg',
                'image_hover' => 'teams/young-cleaning-man-wearing-casual-clothes.jpg',
                'gallery' => [
                    'avatar/happy-customer-05.jpg',
                    'coffe-shop-with-wooden-walls-unfocused.jpg',
                ],
                'tags' => ['Ta’lim', 'Professional', 'Sifat'],
            ],
            [
                'title' => 'To‘y va tadbirlar bezagi: unutilmas kecha uchun hamma narsa bir joyda',
                'category' => 'Tadbirlar va to‘yxonalar',
                'author' => $blogger2,
                'short_content' => 'Bezatish, mehmonxona xizmati va tashkilot ishlari — bayramingizni biz uyushtiramiz.',
                'content' => <<<'TEXT'
To‘y, yubiley yoki korporativ tadbir — bularning barchasi bir marta bo‘ladigan lahzalar. Ularni unutilmas qilish uchun bezatish, texnik jihozlar va tashkilot ishlarini biz o‘z zimmamizga olamiz.

Xizmat tarkibi:
- Zal va stollarni bezatish
- Gullar, chiroqlar va dekoratsiya
- Foto va video xizmati
- Musiqa va ovoz jihozlari
- Mehmonlarni kutib olish va xizmat ko‘rsatish
- Tadbir oxirida tozalash

Ish jarayoni:
1. Brif. Sana, joy va mehmonlar sonini aniqlaymiz.
2. Konsepsiya. Rang va uslub bo‘yicha taklif tayyorlaymiz.
3. Tayyorgarlik. Barcha jihoz va materiallarni olib kelamiz.
4. O‘rnatish. Tadbir boshlanishidan oldin hammasini tayyorlaymiz.
5. Tadbir kunida. Jamoa joyida bo‘lib, hamma narsani nazorat qiladi.

Narx nimaga bog‘liq?
- Mehmonlar soni va zal maydoni
- Dekoratsiya hajmi
- Qo‘shimcha xizmatlar (foto, musiqa)

Kafolat: kelishilgan vaqtda tayyor bo‘lmassak, to‘lovning 20 foizini qaytaramiz.

Kontakt orqali tadbir tafsilotlarini yozing — taklif tayyorlaymiz.
TEXT,
                'price' => 3500000,
                'duration' => 10,
                'image' => 'coffe-shop-with-wooden-walls-unfocused.jpg',
                'image_hover' => 'slideshow/unrecognizable-cleaner-walking-into-hotel-room-with-tools-detergents.jpg',
                'gallery' => [
                    'avatar/happy-customer-02.jpg',
                    'teams/happy-young-woman-wiping-kitchen-counter-wearing-yellow-gloves.jpg',
                ],
                'tags' => ['Tadbirlar', 'Ijodiy', 'Kafolat'],
            ],
            [
                'title' => 'Maishiy texnika va elektronikani ta’mirlash: uyda qulay xizmat',
                'category' => 'Ta’mirlash va remont',
                'author' => $blogger2,
                'short_content' => 'Muzlatgich, kir yuvish mashinasi va boshqa texnikangizni uyingizda, tez va shaffof narxda ta’mirlaymiz.',
                'content' => <<<'TEXT'
Maishiy texnika buzilsa, yangisini olish shoshilinch emas. Ko‘pgina muammolarni uyingizda, qisqa vaqt ichida va arzonroq bartaraf etish mumkin.

Xizmat tarkibi:
- Muzlatgich, kir yuvish va idish yuvish mashinalari
- Pech, mikroto‘lqin va kaput
- Konditsioner va isitish tizimlari
- Televizor va kichik elektronika
- Diagnostika va ehtiyot qismlar almashtirish

Ish jarayoni:
1. Ariza. Muammoni telefonda yoki formada yozing.
2. Diagnostika. Ustoz kelib, aniq sababni topadi.
3. Narx. Ta’mirlash va qismlar narxini oldin aytamiz.
4. Bajarish. Ko‘p hollarda uyda 1–2 soatda tugatamiz.
5. Kafolat. Bajarilgan ishga 6 oy kafolat beramiz.

Narx nimaga bog‘liq?
- Texnika turi va nosozlik
- Ehtiyot qism narxi
- Chiqish xizmati

E’tibor: narxni oldin kelishamiz, rozilik bermasangiz hech qanday to‘lov yo‘q.

Kontakt orqali texnika turi va alomatni yozing.
TEXT,
                'price' => 250000,
                'duration' => 3,
                'image' => 'services/young-smiling-woman-wearing-rubber-gloves-cleaning-stove.jpg',
                'image_hover' => 'services/woman-holding-rag-detergent-cleaning-cooker.jpg',
                'gallery' => [
                    'services/close-up-mop-cleaning-industrial-plant-floor.jpg',
                ],
                'tags' => ['Ta’mirlash', 'Tez xizmat', 'Kafolat'],
            ],
        ];

        foreach ($posts as $data) {
            $category = Category::where('name', $data['category'])->firstOrFail();

            $post = Post::updateOrCreate(
                ['title' => $data['title']],
                [
                    'user_id' => $data['author']->id,
                    'category_id' => $category->id,
                    'short_content' => $data['short_content'],
                    'content' => $data['content'],
                    'price' => $data['price'],
                    'duration' => $data['duration'],
                    'image' => $this->copyImage($data['image']),
                    'image_hover' => $this->copyImage($data['image_hover']),
                    'gallery_images' => array_map(
                        fn (string $image): string => $this->copyImage($image),
                        $data['gallery'],
                    ),
                ],
            );

            $tagIds = Tag::whereIn('name', $data['tags'])->pluck('id');
            $post->tags()->sync($tagIds);
        }
    }

    private function copyImage(string $relativePath): string
    {
        $destination = 'post-images/'.basename($relativePath);

        if (! Storage::disk('public')->exists($destination)) {
            $source = public_path('images/'.$relativePath);

            if (! is_file($source)) {
                throw new RuntimeException("Rasm topilmadi: {$source}");
            }

            Storage::disk('public')->put($destination, file_get_contents($source));
        }

        return $destination;
    }
}
