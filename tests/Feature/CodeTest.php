<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Code as Code;
use App\Models\Door as Door;
use App\Models\User;

class CodeTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_list(): void
    {
        $user = User::factory()->create();
        $response =  $this->actingAs($user)->get('/code/list');

        $response->assertStatus(200);
    }

    public function test_generate(): void
    {
        $user = User::factory()->create();
        $response =  $this->actingAs($user)->post('/code/generate',[
            'generate'=>true,
            'number'=>100
        ]);

        $response->assertStatus(200);
    }

    public function test_detail(): void
    {
        
        $user = User::factory()->create();
        $this->actingAs($user)->post('/code/generate',[
            'generate'=>true,
            'number'=>100
        ]);
        $code = Code::first();
        $response =  $this->actingAs($user)->get('/code/detail/'.$code->id);

        $response->assertStatus(200);
    }

    public function test_edit(): void
    {

        $user = User::factory()->create();
        $this->actingAs($user)->post('/code/generate',[
            'generate'=>true,
            'number'=>100
        ]);
        $code = Code::first();
        $door = Door::create(['name'=>'test','status'=>1]);
        $response =  $this->actingAs($user)->post('/code/edit/'.$code->id,[
            'door'=>$door->id
        ]);

        $response->assertStatus(200);
    }
}
