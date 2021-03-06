<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\Http\Resources\Contact as ContactResource;
// use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',Contact::class);
        return ContactResource::collection(request()->user()->contacts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // \policy
        $this->authorize('create',Contact::class);

        //  automatically assign current user to contact created
        $contact = request()->user()->contacts()->create($this->validate_data());
         // return \response($contact,201);
        //  return resource and status created code of 201
        return (new ContactResource($contact))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        $this->authorize('view',$contact);


        return new ContactResource($contact);
    }

    /** 
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        // policy
        $this->authorize('update',$contact);
        Contact::update($this->validate_data());
        return (new ContactResource($contact))
                ->response()
                ->setStatusCode(Response::HTTP_OK);

    }

    private function validate_data(){
         return  request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'birthday' => 'required',
            'company'  => 'required'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $this->authorize('delete',$contact);
        $contact->delete();
    }
}
