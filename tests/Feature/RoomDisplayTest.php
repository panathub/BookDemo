<?php

namespace Tests\Feature;

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
    }

    public function test_public_upcoming_booking_json_carries_no_user_credentials(): void
    {
        $this->get('/getBookingKara')
            ->assertOk()
            ->assertJsonPath('details.BookingTitle', 'Demo planning')
            ->assertJsonMissingPath('details.password')
            ->assertJsonMissingPath('details.remember_token')
            ->assertJsonMissingPath('details.email');
    }
}
