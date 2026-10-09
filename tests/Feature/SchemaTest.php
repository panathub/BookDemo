<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SchemaTest extends TestCase
{
    use RefreshDatabase;

    public static function liveTables(): array
    {
        $booking = ['BookingTitle', 'BookingAmount', 'Booking_start', 'Booking_end', 'BookingDetail', 'BookingStatus', 'RoomStatus', 'VerifyStatus', 'id', 'RoomID'];

        return [
            'rooms' => ['rooms', ['RoomID', 'RoomName', 'RoomNumber', 'RoomAmount', 'RoomStatus', 'Image_room', 'slug', 'theme']],
            'department' => ['department', ['DepartmentID', 'DepartmentName']],
            'role' => ['role', ['roleID', 'roleName']],
            'reports' => ['reports', array_merge(['ReportID'], $booking)],
            'bookings' => ['bookings', array_merge(['BookingID'], $booking, ['ReportID', 'deleted_at'])],
            'modal' => ['modal', ['id', 'image', 'text']],
            'accessories' => ['accessories', ['AccessoriesID', 'Name', 'Quantity', 'Image_acc']],
            'users' => ['users', ['id', 'name', 'email', 'picture', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at', 'DepartmentID', 'roleID']],
            'password_resets' => ['password_resets', ['email', 'token', 'created_at']],
        ];
    }

    #[DataProvider('liveTables')]
    public function test_table_has_exactly_the_live_columns($table, $columns)
    {
        $this->assertEqualsCanonicalizing($columns, Schema::getColumnListing($table));
        $this->assertCount(0, DB::table($table)->select($columns)->get());
    }
}
