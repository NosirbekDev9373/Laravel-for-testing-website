<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $comments = [
            [
                'post' => 'Kvartirani 4 soatda mukammal tozalash: chang, dog‘ va hidlardan xalos bo‘ling',
                'user' => 'aziza@tozalash.uz',
                'body' => 'Kvartirani 3 soatda tozalab berishdi. Derazalar va oshxona ayniqsa yaxshi chiqdi. Endi har oy chaqiramiz.',
            ],
            [
                'post' => 'Gilam va yumshoq mebelni chuqur yuvish: 24 soatda quritamiz',
                'user' => 'malika@tozalash.uz',
                'body' => 'Divanimdagi eski dog‘lar ketdi, hidi ham yo‘qoldi. 24 soatdan keyin bemalol ishlatsa bo‘ladi.',
            ],
            [
                'post' => 'Ofis tozalash: xodimlar kelishidan oldin toza va sog‘lom muhit',
                'user' => 'bekzod@tozalash.uz',
                'body' => 'Ofisimizni haftada bir marta tozalab turishadi. Xodimlar ham, mijozlar ham farqni sezdi.',
            ],
            [
                'post' => 'Dezinfeksiya xizmati: virus va bakteriyalardan 99,9% himoya',
                'user' => 'nigora@tozalash.uz',
                'body' => 'Bolalar bog‘chasi uchun dezinfeksiya qildirdik. Hujjatlar va sertifikatlar ham berildi, juda professional.',
            ],
            [
                'post' => 'Avtomobil salonini kimyoviy tozalash: yangi mashina effekti',
                'user' => 'rustam@tozalash.uz',
                'body' => 'Mashinam salonini tozalashdi — xuddi yangi mashina olgandek bo‘ldim. Narxi ham munosib.',
            ],
            [
                'post' => 'Deraza va oynalarni iz qoldirmasdan yuvish: yorug‘lik 2 barobar ko‘proq',
                'user' => 'aziza@tozalash.uz',
                'body' => 'Derazalar shunday toza bo‘ldiki, uy yorug‘roq bo‘lib qoldi. Ish vaqtida hech qanday chang qoldirmadi.',
            ],
            [
                'post' => 'Oshxona va maishiy texnikani chuqur tozalash: yog‘ va kuyindilardan xalos bo‘ling',
                'user' => 'malika@tozalash.uz',
                'body' => 'Plita va kaputdagi yog‘ni tozalash qiyin edi, endi oson. Rahmat!',
            ],
            [
                'post' => 'Kafe va restoranlar uchun sanitariya tozalash: mijozlar ishonchi uchun',
                'user' => 'bekzod@tozalash.uz',
                'body' => 'Kafemiz uchun sanitariya tozalash qildirdik. Mijozlar ko‘paydi, gigiena muhim ekan.',
            ],
            [
                'post' => 'Kichik biznes uchun brending: birinchi taassurotni professional qiling',
                'user' => 'nigora@tozalash.uz',
                'body' => 'Vizitka va logo dizayni juda chiroyli chiqdi. Mijozlar birinchi qarashda ishonch bildirishadi.',
            ],
            [
                'post' => 'Uy va ofis remonti: smetadan topshirishgacha shaffof jarayon',
                'user' => 'rustam@tozalash.uz',
                'body' => 'Smeta bo‘yicha ish qilishdi, hech qanday qo‘shimcha to‘lov so‘ramadilar. Vaqtida topshirishdi.',
            ],
            [
                'post' => 'Veb-sayt va raqamli platforma yaratish: biznesingizni onlaynga olib chiqamiz',
                'user' => 'bekzod@tozalash.uz',
                'body' => 'Veb-saytimiz 2 haftada tayyor bo‘ldi. Tez, qulay va narxi oldindan aniq edi.',
            ],
            [
                'post' => 'Go‘zallik va parvarish xizmatlari: o‘zingizga vaqt ajrating',
                'user' => 'malika@tozalash.uz',
                'body' => 'Yuz muolajasi juda yoqdi, mutaxassis juda e’tiborli edi. Yana kelaman.',
            ],
            [
                'post' => 'Individual darslar va konsalting: maqsadingizga tezroq yeting',
                'user' => 'aziza@tozalash.uz',
                'body' => 'Birinchi bepul darsdan keyin darhol yozildim. Tushuntirishlari juda tushunarli.',
            ],
            [
                'post' => 'To‘y va tadbirlar bezagi: unutilmas kecha uchun hamma narsa bir joyda',
                'user' => 'nigora@tozalash.uz',
                'body' => 'To‘y bezagini ular qilishdi — hamma mehmonlar hayron qoldi. Rahmat!',
            ],
            [
                'post' => 'Maishiy texnika va elektronikani ta’mirlash: uyda qulay xizmat',
                'user' => 'aziza@tozalash.uz',
                'body' => 'Muzyorarim uyda bir soatda tuzatildi. Narxni oldin aytishdi, kutilmagan to‘lov bo‘lmadi.',
            ],
        ];

        foreach ($comments as $data) {
            $post = Post::where('title', $data['post'])->firstOrFail();
            $user = User::where('email', $data['user'])->firstOrFail();

            Comment::firstOrCreate([
                'post_id' => $post->id,
                'user_id' => $user->id,
                'body' => $data['body'],
            ]);
        }
    }
}
