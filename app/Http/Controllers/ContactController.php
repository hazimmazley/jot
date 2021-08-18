<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'name' =>'required',
            'email' => 'required|email',
            'birthday' => 'required',
            'company' => 'required',     
        ]);

        Contact::create([
            'name' => request('name'),
            'email' => request('email'),
            'birthday' => request('birthday'),
            'company' => request('company'),
        ]);
    }

    public function show(Contact $contact)
    {
        return $contact;
    }

    // public function update(Contact $contact)
    // {
    //     $data = request()->validate([
    //         'name' =>'required',
    //         'email' => 'required|email',
    //         'birthday' => 'required',
    //         'company' => 'required',     
    //     ]);

    //     $contact->update([
    //         'name' => request('name'),
    //         'email' => request('email'),
    //         'birthday' => request('birthday'),
    //         'company' => request('company'), 
    //     ])
    // }

    public function destroy(Contact $contact)
    {
        $contact->delete();
    }
}
