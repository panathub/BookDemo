<?php

namespace Tests\Feature;

use App\Models\Bookings;
use App\Models\Department;
use App\Models\Report;
use App\Models\Room;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RelationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoSeeder::class);
    }

    public function test_room_bookings_are_the_bookings_in_that_room()
    {
        $this->assertSame(
            Bookings::where('RoomID', 63)->pluck('BookingID')->all(),
            Room::find(63)->bookings->pluck('BookingID')->all()
        );
        $this->assertCount(1, Room::find(63)->bookings);
    }

    public function test_department_bookings_are_the_bookings_of_its_users()
    {
        $this->assertCount(3, Department::find(2)->bookings);
        $this->assertCount(0, Department::find(1)->bookings);
    }

    public function test_report_booking_shares_its_report_id()
    {
        $row = [
            'BookingTitle' => 'Distinct ids',
            'BookingAmount' => 2,
            'Booking_start' => '2030-01-01 09:00:00',
            'Booking_end' => '2030-01-01 10:00:00',
            'VerifyStatus' => 0,
            'id' => 1,
            'RoomID' => 60,
        ];
        DB::table('reports')->insert($row + ['ReportID' => 50]);
        DB::table('bookings')->insert($row + ['BookingID' => 7, 'ReportID' => 50]);

        $this->assertSame(7, Report::find(50)->bookings->BookingID);
    }
}
