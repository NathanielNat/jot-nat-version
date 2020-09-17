<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Contact;
use App\User ;

class ContactsTest extends TestCase
{
  use RefreshDatabase;

  protected $user;

  protected function setup():void
  {

    parent::setup();

    $this->user = \factory(User::class)->create();

  }
  
  /** @test */
  public function a_list_of_contacts_can_be_fetched_for_authenticated_user(){
      $user = \factory(User::class)->create();
      $another_user = \factory(User::class)->create();
      
      $contact = \factory(Contact::class)->create(['user_id' => $user->id]);
      $another_contact = \factory(Contact::class)->create(['user_id' => $another_user->id]);

      $response = $this->get('/api/contacts?api_token='. $user->api_token);

      $response->assertJsonCount(1)
                ->assertJson([['id' => $contact->id]]);
  }

   /** @test */
   public function unauthenticated_user_should_be_rediercted_to_login(){
      
      $response =  $this->post('/api/contacts', \array_merge($this->data(),['api_token' => '']));
      $response->assertRedirect('/login');

      $this->assertCount(0, Contact::all());
   }
    /**
     * A basic feature test example.
     *
     * @test  
     */
    public function authenticated_user_can_add_contact(){

      $this->withoutExceptionHandling();
          // dd($this->user->id);
      $user  = factory(User::class)->create();
    

      $this->post('/api/contacts',$this->data(), ['api_token' => $user->api_token]);
       
          $contact = Contact::first();
        
        $this->assertEquals("T'Challa", $contact->name);
        $this->assertEquals('psalmnat@gmail.com', $contact->email);
        $this->assertEquals('05/04/1998', $contact->birthday->format('m/d/Y'));
        $this->assertEquals('IT consortium', $contact->company);
    }

    /**clear && vendor/bin/phpunit --filter a_contact_can_be_deleted
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
            $this->assertEquals('05/04/1998',Contact::first()->birthday->format('m/d/Y'));
     
    }

    /** @test */
    public function a_contact_can_be_retrieved(){
      $this->withoutExceptionHandling();
      $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);

      $response = $this->get('/api/contacts/' . $contact->id . '?api_token=' . $this->user->api_token);

      $response->assertJson([
          'data' => [
              'contact_id' => $contact->id,
              'name' => $contact->name,
              'email' => $contact->email,
              'birthday' => $contact->birthday->format('m/d/Y'),
              'company' => $contact->company,
              'last_updated' => $contact->updated_at->diffForHumans(),
          ]
      ]);
    }

    /** @test */
    public function only_the_users_contact_can_be_retrieved(){
      $contact = factory(Contact::class)->create(['user_id'=>$this->user->id]);
      $anotherUser = \factory(User::class)->create();
       
      $response = $this->get('/api/contacts/'.$contact->id. '?api_token='. $anotherUser->api_token);

      $response->assertStatus(403);
    }


    /** @test */

    public function a_contact_can_be_patched(){
      $contact = factory(Contact::class)->create(['user_id'=> $this->user->id]);
      $response = $this->patch('/api/contacts/'.$contact->id,$this->data());
      $contact = $contact->fresh();
        $this->assertCount(1,Contact::all());
            $this->assertInstanceOf(Carbon::class, Contact::first()->birthday); 
            $this->assertEquals('05/04/1998',Contact::first()->birthday->format('m/d/Y'));
    }

    /** @test */
    public function only_owner_of_contact_can_patch_contact(){
      $contact = factory(Contact::class)->create(['user_id'=> $this->user->id]);

      $anotherUser = \factory(User::class)->create(); 
      $response = $this->patch('/api/contacts/'.$contact->id, array_merge($this->data(),['api_token' => $anotherUser->api_token]));
      
      $response->assertStatus(403);
        
    }

    /** @test */
    public function a_contact_can_be_deleted(){
      $contact = factory(Contact::class)->create(['user_id'=> $this->user->id]);

      $response = $this->delete('/api/contacts/'.$contact->id, ['api_token' => $this->user->api_token]);
      $this->assertCount(0,Contact::all());

    }

        /** @test */
    public function only_owner_of_contact_can_delete_contact(){
      $contact = factory(Contact::class)->create(['user_id'=> $this->user->id]);

      $anotherUser = \factory(User::class)->create(); 
      $response = $this->delete('/api/contacts/'.$contact->id, ['api_token' => $anotherUser->api_token]);
      
      // making an assertion of getting an erorr status of 403 if the user is not  authorised 
      $response->assertStatus(403);

    }


    private function data(){
      return [
        'name' => "T'Challa",
        'email' => 'psalmnat@gmail.com',
        'birthday' => '05/04/1998',
        'company' => 'IT consortium',
        'api_token' => $this->user->api_token,
      ]; 

    }
}
