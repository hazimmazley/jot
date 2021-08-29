<?php

namespace Tests\Feature;

use App\User;
use App\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BirthdaysTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_contacts_with_birthdays_in_the_current_month_can_fetched()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $birthdayContact = factory(Contact::class)->create([
                'user_id' => $user->id,
                'birthday' => now()->subYear(),
            ]);

        $nobirthdayContact = factory(Contact::class)->create([
            'user_id' => $user->id,
            'birthday' => now()->subMonth(),
        ]);

        $response = $this->get('/api/birthdays?api_token='. $user->api_token)
        ->assertJsonCount(1)
        ->assertJson([
            'data' => [
                [
                    'data' => [
                        'contact_id' => $birthdayContact->id,
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
    }
}
