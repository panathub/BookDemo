<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_with_default_role()
    {
        $this->seed(DemoSeeder::class);

        $this->from('/register')->post('/register', [
            'name' => 'New User',
            'email' => 'new-user@example.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'agree' => 'on',
        ])->assertRedirect('/register')->assertSessionHasNoErrors();

        $user = User::where('email', 'new-user@example.test')->firstOrFail()->getAttributes();
        $this->assertSame(2, (int) $user['roleID']);
        $this->assertNull($user['picture']);
    }
}
