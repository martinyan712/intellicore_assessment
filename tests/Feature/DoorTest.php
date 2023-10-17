<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Code as Code;
use App\Models\Door as Door;
use App\Models\User;

class DoorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_list(): void
    {
        $user = User::factory()->create();
        $response =  $this->actingAs($user)->get('/door/list');

        $response->assertStatus(200);
    }

    public function test_create(): void
    {
        $user = User::factory()->create();
        $response =  $this->actingAs($user)->post('/door/create',[
            'name'=>'Main'
        ]);

        $response->assertStatus(200);
    }

    public function test_detail(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/door/create',[
            'name'=>'Main'
        ]);
        $door = Door::first();
     
        $response =  $this->actingAs($user)->get('/door/detail/'.$door->id);

        $response->assertStatus(200);
    }

    public function test_edit(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/door/create',[
            'name'=>'Main'
        ]);
        $this->actingAs($user)->post('/code/generate',[
            'generate'=>true,
            'number'=>100
        ]);
        $door = Door::first();
        $code = Code::doesntHave('doors')->orWhere(function ($query) use ($door){
            $query->whereRelation('doors','doors_codes.door_id',$door->id);
        })->first();


       
        $response =  $this->actingAs($user)->post('/door/edit/'.$door->id,[
            'code'=>$code->id,
            'status'=>1
        ]);

        $response->assertStatus(200);
    }
}
