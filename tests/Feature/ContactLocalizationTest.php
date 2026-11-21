<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_page_is_translated_to_english(): void
    {
        $this->get(route('locale.change', 'en'));

        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Send message');
        $response->assertSee('Your name');
        $response->assertSee('Service type');
        $response->assertSee('Write your message');
        $response->assertDontSee('Xabar yuborish');
    }

    public function test_contact_page_is_translated_to_russian(): void
    {
        $this->get(route('locale.change', 'ru'));

        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Отправить сообщение');
        $response->assertSee('Ваше имя');
        $response->assertSee('Тип услуги');
        $response->assertSee('Напишите сообщение');
        $response->assertDontSee('Xabar yuborish');
    }

    public function test_success_message_is_translated(): void
    {
        Mail::fake();
        $this->get(route('locale.change', 'en'));

        $this->post(route('contact.store'), [
            'name' => 'Ali Valiyev',
            'email' => 'ali@example.com',
            'service_type' => 'Tozalash',
            'message' => 'Salom, xizmat haqida ma’lumot olmoqchiman.',
        ])->assertSessionHas('success', 'Your message has been sent. We will contact you soon.');
    }
}
