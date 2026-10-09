<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomDisplayController extends Controller
{
    public function show(Room $room): View
    {
        return view('room-display', ['room' => $room, 'rooms' => Room::orderBy('RoomName')->get()]);
    }

    public function upcoming(Room $room): JsonResponse
    {
        return response()->json(['details' => Bookings::upcomingFor($room)->first()?->kioskSummary()]);
    }

    public function verify(Room $room, Request $request): JsonResponse
    {
        $booking = Bookings::upcomingFor($room)->find($request->integer('bkid'));
        if ($booking === null) {
            return response()->json(['code' => 0, 'msg' => 'ไม่พบการจองที่อนุมัติแล้วสำหรับห้องนี้']);
        }
        $booking->confirmMeeting();

        return response()->json(['code' => 1, 'msg' => 'ยืนยันการจองห้องประชุม']);
    }
}
