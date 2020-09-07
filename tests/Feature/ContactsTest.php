<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
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
       

        $this->post('/api/contacts', [
          'name' => "T'Challa",
          'email' => 'psalmnat@gmail.com',
          'birthday' => '05/04/1998',
          'company' => 'IT consortium'
          ]);
          $contact = Contact::first();
        // $this->assertCount(1 ,$contact);
        $this->assertEquals("T'Challa", $contact->name);
        $this->assertEquals('psalmnat@gmail.com', $contact->email);
        $this->assertEquals('05/04/1998', $contact->birthday);
        $this->assertEquals('IT consortium', $contact->company);
    }

    /**
     * @test
     */
    public function fields_are_required(){
      // $this->withoutErrorHandling();
      
      collect(['name','email','birthday','company'])
        ->each(function($field) {
            $response = $this->post('/api/contacts',
              array_merge($this->data(),[$field => '']));

              $response->assertSessionHasErrors($field);
              $this->assertCount(0,Contact::all());
        });

      
    }

    /**
     * @test
     */
     public function email_must_be_valid(){
      collect(['name','email','birthday','company'])
      ->each(function($field) {
          $response = $this->post('/api/contacts',
            array_merge($this->data(),['email' => 'Not valid email']));

            $response->assertSessionHasErrors('email');
            $this->assertCount(0,Contact::all());
      });
     }

     /** @test  */
    public function birthdays_are_properly_stored(){
     
          $response = $this->post('/api/contacts',
            array_merge($this->data()));

            $this->assertCount(1,Contact::all());
            $this->assertInstanceOf(Carbon::class, Contact::first()->birthday); 
            $this->assertEquals('05-04-1998',Contact::first()->birthday->format('m-d-Y'));
     
    }

    /** @test */
    public function a_contact_can_be_retrieved(){
      
    }


    private function data(){
      return [
        'name' => "T'Challa",
          'email' => 'psalmnat@gmail.com',
          'birthday' => '05/04/1998',
          'company' => 'IT consortium'
      ]; 

    }
}
