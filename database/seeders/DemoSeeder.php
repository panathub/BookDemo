<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run()
    {
        DB::table('role')->insert([
            ['roleID' => 1, 'roleName' => 'admin'],
            ['roleID' => 2, 'roleName' => 'user'],
        ]);

        DB::table('department')->insert([
            ['DepartmentID' => 1, 'DepartmentName' => 'Demo Operations'],
            ['DepartmentID' => 2, 'DepartmentName' => 'Demo Marketing'],
        ]);

        DB::table('rooms')->insert(array_map(
            fn ($room) => array_combine(['RoomID', 'RoomName', 'RoomNumber', 'RoomAmount', 'RoomStatus', 'Image_room'], $room),
            [
                [60, 'Tonkotsu', '#6af3f6', 9, 0, 'TONKOTSU.jpg'],
                [63, 'Karamiso', '#ff512f', 9, 0, 'KARAMISO.jpg'],
                [64, 'Sukiyaki', '#fbf323', 20, 0, 'SUKIYAKI.jpg'],
                [66, 'Shabushabu (ใช้เฉพาะผู้บริหารเท่านั้น)', '#2b86c5', 20, 0, 'SHABU-SHABU.jpg'],
                [67, 'Kinoko', '#FF3CAC', 9, 0, 'KINOKO.jpg'],
                [78, 'PONZU', '#1dd51a', 12, 0, 'S__173015057.jpg'],
                [79, 'OIL SAUCE', '#eab3f9', 4, 0, 'OIL SAUCE.jpg'],
                [80, 'SESAME', '#202022', 3, 0, 'SESAME.jpg'],
                [81, 'SWEET SHOYU', '#8f4d0f', 6, 0, 'SWEET SHOYU.jpg'],
                [82, 'WARISHITA', '#340df8', 9, 0, 'WARISHITA.jpg'],
            ]
        ));

        DB::table('modal')->insert([
            'id' => 1,
            'image' => 'logo1.jpg',
            'text' => 'ประกาศตัวอย่าง: กรุณาจองห้องประชุมล่วงหน้า',
        ]);

        $now = Carbon::now();
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Demo Admin', 'email' => 'demo-admin@example.test', 'password' => Hash::make('password'), 'DepartmentID' => 1, 'roleID' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Demo User', 'email' => 'demo-user@example.test', 'password' => Hash::make('password'), 'DepartmentID' => 2, 'roleID' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $tomorrow = Carbon::tomorrow();
        $bookings = [
            [1, 'Demo kickoff', 60, $tomorrow->copy()->setTime(9, 0), 1],
            [2, 'Demo planning', 63, $tomorrow->copy()->setTime(13, 0), 1],
            [3, 'Demo review', 64, $tomorrow->copy()->addDay()->setTime(10, 0), 0],
        ];

        foreach ($bookings as [$id, $title, $roomId, $start, $verified]) {
            $row = [
                'BookingTitle' => $title,
                'BookingAmount' => 4,
                'Booking_start' => $start,
                'Booking_end' => $start->copy()->addHour(),
                'VerifyStatus' => $verified,
                'id' => 2,
                'RoomID' => $roomId,
            ];
            DB::table('reports')->insert($row + ['ReportID' => $id]);
            DB::table('bookings')->insert($row + ['BookingID' => $id, 'ReportID' => $id]);
        }
    }
}
