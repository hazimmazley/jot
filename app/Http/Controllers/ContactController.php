<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Contact::class);

        return request()->user()->contacts();
    }

    public function store()
    {
        $this->authorize('create', Contact::class);

        $data = request()->validate([
            'name' =>'required',
            'email' => 'required|email',
            'birthday' => 'required',
            'company' => 'required',     
        ]);

        request()->user()->contacts()->create([
            'name' => request('name'),
            'email' => request('email'),
            'birthday' => request('birthday'),
            'company' => request('company'),
        ]);
    }

    public function show(Contact $contact)
    {
       $this->authorize('view', $contact);

        return $contact;
    }


    public function update(Contact $contact)
    {
        $this->authorize('update', $contact);

        $data = request()->validate([
            'name' =>'required',
            'email' => 'required|email',
            'birthday' => 'required',
            'company' => 'required',     
        ]);

        $contact->update([
            'name' => request('name'),
            'email' => request('email'),
            'birthday' => request('birthday'),
            'company' => request('company'), 
        ])
    }

    public function destroy(Contact $contact)
    {
        $this->authorize('delete', $contact);

        $contact->delete();
    }
}
