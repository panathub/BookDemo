<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_link_request_writes_to_password_resets()
    {
        $this->seed(DemoSeeder::class);

        $this->from('/password/reset')
            ->post('/password/email', ['email' => 'demo-user@example.test'])
            ->assertRedirect('/password/reset')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('password_resets', ['email' => 'demo-user@example.test']);
    }

    public function test_reset_with_valid_token_sends_user_to_user_dashboard()
    {
        $this->seed(DemoSeeder::class);
        $user = User::find(2);
        $token = Password::broker()->createToken($user);

        $this->post('/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('user.dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }
}
