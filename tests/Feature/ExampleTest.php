<?php

namespace Tests\Feature;

use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_the_seeded_rooms()
    {
        $this->seed(DemoSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertViewHas('rooms', fn ($rooms) => count($rooms) === 10 && in_array('Karamiso', $rooms, true));
    }
}
