<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Resources\Contact as ContactResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Contact::class);

        return ContactResource::collection(request()->user()->contacts);
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

        $contact = request()->user()->contacts()->create([
            'name' => request('name'),
            'email' => request('email'),
            'birthday' => request('birthday'),
            'company' => request('company'),
        ]);

        return (new ContactResource($contact))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Contact $contact)
    {
       $this->authorize('view', $contact);

        return new ContactResource($contact);
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
