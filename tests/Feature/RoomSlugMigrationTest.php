<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RoomSlugMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_slugs_win_over_a_lower_id_room_with_the_same_name(): void
    {
        DB::table('rooms')->delete();
        foreach ([70 => 'Ponzu', 78 => 'PONZU', 92 => 'ห้องไทย'] as $id => $name) {
            DB::table('rooms')->insert(['RoomID' => $id, 'RoomName' => $name, 'RoomNumber' => '#1', 'RoomAmount' => 1, 'RoomStatus' => 0, 'Image_room' => 'x', 'slug' => null, 'theme' => Room::DEFAULT_THEME]);
        }

        (require base_path('database/migrations/2026_10_08_000030_add_slug_and_theme_to_rooms_table.php'))->up();

        $this->assertSame([70 => 'ponzu-2', 78 => 'ponzu', 92 => 'room-92'], DB::table('rooms')->orderBy('RoomID')->pluck('slug', 'RoomID')->all());
    }
}
