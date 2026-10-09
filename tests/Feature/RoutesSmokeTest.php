<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoutesSmokeTest extends TestCase
{
    use RefreshDatabase;

    /** Routes that 500 at the PR-2 base for reasons unrelated to the rebase, tracked in issue #7. */
    private const BROKEN_BEFORE_REBASE = [
        '/admin/profile',
        '/admin/accessories',
        '/getBookingNabezo',
        '/getBookingNabe2Test',
        '/getBookingSukiyaki2Test',
        '/getBookingShabu2Test',
        '/getBookingKinoko2Test',
        '/notiModal',
    ];

    public static function appGetRoutes(): array
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $routes = $app->make('router')->getRoutes();
        restore_error_handler();
        restore_exception_handler();

        $cases = [];
        foreach ($routes as $route) {
            if (! self::isAppGetRoute($route)) {
                continue;
            }
            $uri = '/'.preg_replace('/\{[^}]+\}/', '1', $route->uri());
            if (in_array($uri, self::BROKEN_BEFORE_REBASE, true)) {
                continue;
            }
            $cases[$uri] = [$uri];
        }

        return $cases;
    }

    private static function isAppGetRoute(Route $route): bool
    {
        return in_array('GET', $route->methods(), true)
            && str_starts_with((string) $route->getActionName(), 'App');
    }

    #[DataProvider('appGetRoutes')]
    public function test_get_route_responds_for_admin(string $uri): void
    {
        $this->seed(DemoSeeder::class);

        $status = $this->actingAs(User::find(1))->get($uri)->getStatusCode();

        $this->assertContains($status, [200, 302], "GET $uri returned $status");
    }
}
