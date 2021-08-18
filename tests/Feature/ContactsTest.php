<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Contact;
use Carbon\Carbon;

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
        $this->post('/api/contacts', $this->data());

        $contact = Contact::first();

        $this->assertEquals('Test name', $contact->name);
        $this->assertEquals('test@gmail.com', $contact->email);
        $this->assertEquals('05/06/1995', $contact->birthday);
        $this->assertEquals('wkj', $contact->company);
    }

    public function test_fields_are_required(Type $var = null)
    {
        collect(['name', 'email', 'birthday', 'company'])
            ->each(function ($field) {
                $response = $this->post('/api/contacts', array_merge($this->data(), [ $field => '']));

                $response->assertSessionHasErrors($field);
                $this->assertCount(0, Contact::all());
            });
    }

    public function test_email_must_valid(Type $var = null)
    {
        $response = $this->post('/api/contacts', array_merge($this->data(), ['email' => 'wefweh']));

        $response->assertSessionHasErrors('email');
        $this->assertCount(0, Contact::all());
    }

    // public function test_birthday_must_valid(Type $var = null)
    // {
    //     $this->withoutExceptionHandling();
    //     $response = $this->post('/api/contacts', array_merge($this->data(), ['birthday' => 'June 05, 1995']));

    //     $this->assertCount(1, Contact::all());
    //     $this->assertInstanceOf(Carbon::class, Contact::first()->birthday);
    //     $this->assertEquals('05/06/1995', Contact::first()->birthday->format('d/m/Y'));
    // }

    public function test_a_contact_can_be_retrieved(Type $var = null)
    {
        $contact = factory(Contact::class)->create();

        $response = $this->get('/api/contacts/' .$contact->id);

        $response->assertJson([
            'name' => $contact->name,
            'email' => $contact->email,
            'birthday'=> $contact->birthday,
            'company' => $contact->company
        ]);
    }

    // public function test_a_contact_can_be_patched()
    // {
    //     // $this->withoutExceptionHandling();

    //     $contact = factory(Contact::class)->create();

    //     $response = $this->patch('/api/contacts/' .$contact->id, $this->data());

    //     $contact = $contact->fresh();

    //     $this->assertEquals('Test name', $contact->name);
    //     $this->assertEquals('test@gmail.com', $contact->email);
    //     $this->assertEquals('05/06/1995', $contact->birthday);
    //     $this->assertEquals('wkj', $contact->company);
    // }

    public function test_a_contact_can_be_deleted()
    {
        $contact = factory(Contact::class)->create();

        $response = $this->delete('/api/contacts/' .$contact->id);

        $this->assertCount(0, Contact::all());
    }

    private function data(Type $var = null)
    {
        return [
            'name' => 'Test name',
            'email' => 'test@gmail.com',
            'birthday' => '05/06/1995',
            'company' => 'wkj'
        ];
    }
}
