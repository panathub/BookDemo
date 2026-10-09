<?php

namespace Tests\Feature;

use App\Models\Bookings;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoomDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
    }

    public static function seededRooms(): array
    {
        return [
            ['tonkotsu', 'Tonkotsu'],
            ['karamiso', 'Karamiso'],
            ['sukiyaki', 'Sukiyaki'],
            ['shabushabu', 'Shabushabu'],
            ['kinoko', 'Kinoko'],
            ['ponzu', 'PONZU'],
            ['oil-sauce', 'OIL SAUCE'],
            ['sesame', 'SESAME'],
            ['sweet-shoyu', 'SWEET SHOYU'],
            ['warishita', 'WARISHITA'],
        ];
    }

    #[DataProvider('seededRooms')]
    public function test_each_seeded_room_renders_under_its_slug(string $slug, string $name): void
    {
        $this->get("/room/$slug")
            ->assertOk()
            ->assertSee($name)
            ->assertSee(Room::where('slug', $slug)->value('theme'), false);
    }

    public function test_unknown_slug_is_404(): void
    {
        $this->get('/room/nabezo')->assertNotFound();
    }

    public function test_public_upcoming_booking_json_carries_no_user_credentials(): void
    {
        $details = $this->get('/room/karamiso/upcoming')
            ->assertOk()
            ->assertJsonPath('details.BookingTitle', 'Demo planning')
            ->assertJsonPath('details.name', 'Demo User')
            ->assertJsonPath('details.DepartmentName', 'Demo Marketing')
            ->assertJsonMissingPath('details.password')
            ->assertJsonMissingPath('details.remember_token')
            ->assertJsonMissingPath('details.email')
            ->json('details');

        $this->assertSame(
            ['BookingTitle', 'RoomName', 'name', 'DepartmentName', 'Booking_start', 'Booking_end', 'BookingDetail', 'BookingStatus'],
            array_keys($details)
        );
    }

    public function test_room_without_an_approved_booking_renders_and_answers_null(): void
    {
        $this->get('/room/kinoko')->assertOk()->assertSee('Kinoko');
        $this->get('/room/kinoko/upcoming')->assertOk()->assertExactJson(['details' => null]);
    }

    public function test_only_approved_bookings_count_as_upcoming(): void
    {
        $this->get('/room/sukiyaki/upcoming')->assertExactJson(['details' => null]);

        Bookings::find(3)->approve();

        $this->get('/room/sukiyaki/upcoming')->assertJsonPath('details.BookingTitle', 'Demo review');
    }

    public function test_old_room_paths_redirect_permanently(): void
    {
        foreach (['karamiso', 'tonkotsu', 'sukiyaki', 'shabushabu', 'kinoko'] as $slug) {
            $this->get("/$slug")->assertRedirect("/room/$slug")->assertStatus(301);
        }
        $this->get('/nabezo')->assertRedirect('/')->assertStatus(301);
    }

    public function test_guest_cannot_verify(): void
    {
        $this->post('/room/karamiso/verify')->assertRedirect('/login');
        $this->assertSame(0, Bookings::find(2)->BookingStatus);
    }

    public function test_verify_confirms_the_upcoming_booking(): void
    {
        $this->actingAs(User::find(2))->post('/room/karamiso/verify')->assertJson(['code' => 1]);

        $this->assertSame(1, Bookings::find(2)->fresh()->BookingStatus);
        $this->get('/room/karamiso/upcoming')->assertJsonPath('details.BookingStatus', 1);
    }

    public function test_new_room_gets_a_slug_from_its_name_and_renders(): void
    {
        $room = Room::create(['RoomName' => 'Miso Butter', 'RoomNumber' => '#fff', 'RoomAmount' => 8, 'RoomStatus' => 0, 'Image_room' => 'x.jpg']);
        $twin = Room::create(['RoomName' => 'Miso Butter', 'RoomNumber' => '#fff', 'RoomAmount' => 8, 'RoomStatus' => 0, 'Image_room' => 'x.jpg']);

        $this->assertSame('miso-butter', $room->slug);
        $this->assertSame('miso-butter-2', $twin->slug);
        $this->get('/room/miso-butter')->assertOk()->assertSee('Miso Butter')->assertSee(Room::DEFAULT_THEME, false);
        $this->get('/')->assertOk()->assertSee('/room/miso-butter');
    }
}
