<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class Searchcontroller extends Controller
{
    public function index()
    {
        $data = request()->validate([
            'searchTerm' => 'required'
        ]);

        $contacts = Contact::search($data['searchTerm'])
        ->where('user_id', request()->user()->id)
        ->get(); 

        return  \App\Http\Resources\Contact::collection($contacts);
    }  

}
