<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Contact;

class ContactsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_contact_can_be_added()
    {
        $this->withoutExceptionHandling();
        
        $this->post('/api/contacts', ['name' => 'Test name']);

        $this->assertCount(1, Contact::all());
    }
}
