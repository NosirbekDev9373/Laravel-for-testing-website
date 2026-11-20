<?php

namespace Tests\Feature;

use App\Mail\ContactMessageMail;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        config(['mail.contact_address' => 'owner@example.com']);
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ali Valiyev',
            'email' => 'ali@example.com',
            'service_type' => 'Tozalash xizmatlari',
            'message' => 'Salom, xizmat haqida ma’lumot olmoqchiman.',
        ], $overrides);
    }

    public function test_contact_page_renders_real_form_and_categories(): void
    {
        $category = Category::create(['name' => 'Tozalash xizmatlari']);

        $response = $this->get(route('contact'));

        $response->assertOk();
        $response->assertSee('Xabar yuborish');
        $response->assertSee(route('contact.store'), false);
        $response->assertSee($category->name);
        $response->assertSee('Boshqa');
        $response->assertDontSee('action="#"');
        $response->assertDontSee('volunteer-name');
        $response->assertDontSee('Best Cleaning Service');
        $response->assertDontSee('We\'re happy to help');
    }

    public function test_guest_can_submit_contact_message(): void
    {
        Mail::fake();

        $data = $this->validData();

        $response = $this->post(route('contact.store'), $data);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'name' => $data['name'],
            'email' => $data['email'],
            'service_type' => $data['service_type'],
            'message' => $data['message'],
        ]);

        Mail::assertQueued(ContactMessageMail::class, function (ContactMessageMail $mail) use ($data) {
            return $mail->contactMessage->email === $data['email']
                && $mail->hasTo('owner@example.com');
        });
    }

    public function test_contact_form_validates_input(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'service_type' => '',
            'message' => 'short',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'service_type', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_contact_message_has_maximum_length(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), $this->validData([
            'message' => str_repeat('a', 5001),
        ]));

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('contact_messages', 0);
        Mail::assertNothingQueued();
    }

    public function test_contact_form_is_rate_limited(): void
    {
        Mail::fake();

        for ($i = 1; $i <= 5; $i++) {
            $this->post(route('contact.store'), $this->validData([
                'email' => "guest{$i}@example.com",
            ]))->assertRedirect(route('contact'));
        }

        $this->post(route('contact.store'), $this->validData([
            'email' => 'blocked@example.com',
        ]))->assertStatus(429);

        $this->assertDatabaseCount('contact_messages', 5);
        $this->assertDatabaseMissing('contact_messages', [
            'email' => 'blocked@example.com',
        ]);
    }
}
