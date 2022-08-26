<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class CreateUserTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();

        $user = $this->login();
        $user->assignRole('super-admin');
    }


    /** @test */

    public function user_can_create_user_if_data_create_is_valid()
    {
        $this->login();
        Storage::fake('local');
        $imageName = 'image.png';
        $dataCreate = User::factory()->make()->toArray();
        $dataCreate['image'] = UploadedFile::fake()->image($imageName);
        $dataCreate['password'] = $this->faker->text();
        $this->json('POST', route('users.store'), $dataCreate)
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('message');
        $imagePath = User::IMAGE_SAVE_PATH . time() . $imageName;
        Storage::disk('local')->assertExists($imagePath);
    }
}
