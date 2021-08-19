<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Contact;
use Carbon\Carbon;

class ContactsTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(Type $var = null): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

    }

    public function test_a_list_of_contacts_can_be_fected_for_the_authenticated_user()
    {
        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $contact = factory(Contact::class)->create(['user_id' => $user->id]);
        $anotherContact = factory(Contact::class)->create(['user_id' => $anotherUser->id]);

        $response = $this->get('/api/contacts?api_token='.$user->api_token);


        $response->assertJsonCount(1)
        ->assertJson([['id' => $contact->id]]);
        
    }

    public function test_unauthenticated_user_should_redirected_to_login(Type $var = null)
    {
        $response = $this->post('/api/contacts', array_merge($this->data(), ['api_token' => '']));

        $response->assertRedirect('/login');
        $this->assertCount(0, Contact::all());
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_authenticated_user_can_add_a_contact()
    {
        $this->withoutExceptionHandling();



        $this->post('/api/contacts',$this->data());

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
        $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);

        $response = $this->get('/api/contacts/' .$contact->id.'?api_token='.$this->user->api_token);

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

        $response = $this->delete('/api/contacts/' .$contact->id,   ['api_token' => $this->user->api_token]);

        $this->assertCount(0, Contact::all());
    }

    private function data(Type $var = null)
    {
        return [
            'name' => 'Test name',
            'email' => 'test@gmail.com',
            'birthday' => '05/06/1995',
            'company' => 'wkj',
            'api_token' => $this->user->api_token
        ];
    }
}
