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
     * @test  
     */
    public function contact_can_be_added(){

        //show error
        $this->withoutExceptionHandling();
        
        $this->post('/api/contacts',['name' => "T'Challa"]);

        $this->assertCount( 1 ,Contact::all());
    }
}
