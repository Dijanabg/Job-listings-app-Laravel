<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ListingController extends Controller
{
    public function index(){
        return view('listings.index', ['listings'=>Listing::latest()->filter(request(['tag']))->paginate(4)]);//or simplePaginate for next..
    }
    //show single listing
    public function show(Listing $listing){
        return view('listings.show', ['listing'=>$listing]);
    }
    //show create form
    public function create(){
        return view('listings.create');
    }
    //store
    public function store(Request $request){
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'logo' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        //$formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfully!');
    }
    
}
