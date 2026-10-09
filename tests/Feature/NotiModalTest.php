<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotiModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_notice_modal_loads()
    {
        $this->seed();

        $this->actingAs(User::find(1))
            ->get('/notiModal')
            ->assertOk()
            ->assertJsonPath('details.image', 'logo1.jpg');
    }

    public function test_empty_notice_table_answers_null_instead_of_500(): void
    {
        $this->get('/notiModal')
            ->assertOk()
            ->assertExactJson(['details' => null]);
    }
}
