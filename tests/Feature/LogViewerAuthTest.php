<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class LogViewerAuthTest extends TestCase
{
    private const UI_HEADERS = [
        'Referer' => 'http://localhost/admin/log-viewer',
        'X-Requested-With' => 'XMLHttpRequest',
        'Accept' => 'application/json',
    ];

    /**
     * actingAs() sets the guard's user directly and skips the session cookie,
     * which is the part the API middleware can break. Sign in through a real
     * encrypted session cookie instead, backed by an in-memory user provider.
     */
    private function signInWithSessionCookie(int $roleID): void
    {
        $user = new User(['roleID' => $roleID]);
        $user->id = 1;

        Auth::provider('in-memory', function () use ($user) {
            return new class($user) implements UserProvider
            {
                private $user;

                public function __construct(User $user)
                {
                    $this->user = $user;
                }

                public function retrieveById($identifier)
                {
                    return $identifier == $this->user->id ? $this->user : null;
                }

                public function retrieveByToken($identifier, $token)
                {
                    return null;
                }

                public function updateRememberToken(Authenticatable $user, $token) {}

                public function retrieveByCredentials(array $credentials)
                {
                    return null;
                }

                public function validateCredentials(Authenticatable $user, array $credentials)
                {
                    return false;
                }

                public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false) {}
            };
        });
        config(['auth.providers.users.driver' => 'in-memory']);

        $sessionId = Str::random(40);
        $this->app['session']->driver()->getHandler()->write(
            $sessionId,
            json_encode([Auth::guard()->getName() => $user->id])
        );
        $this->withCookie(config('session.cookie'), $sessionId);
    }

    public function test_guest_is_redirected_from_log_viewer_page()
    {
        $this->get('/admin/log-viewer')->assertRedirect('/login');
    }

    public function test_guest_json_request_to_api_is_unauthenticated()
    {
        $this->getJson('/admin/log-viewer/api/folders')->assertStatus(401);
    }

    public function test_admin_ui_request_to_api_succeeds()
    {
        $this->signInWithSessionCookie(1);

        $this->get('/admin/log-viewer/api/folders', self::UI_HEADERS)->assertStatus(200);
    }

    public function test_non_admin_ui_request_to_api_is_rejected()
    {
        $this->signInWithSessionCookie(2);

        $this->get('/admin/log-viewer/api/folders', self::UI_HEADERS)->assertRedirect('/login');
    }
}
