<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Room;

class CheckRoomController extends Controller
{
	public function index()
	{
		return view('dashboards.users.checkroom-nabezo');
	}

	//GET BOOKING DETAILS 
	public function view($RoomID)
	{
		$data = Bookings::select('users.*', 'rooms.*', 'department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->where('rooms.RoomID', $RoomID)
			->orderBy('Booking_start', 'asc')
			->get();
		$room = Room::where('RoomID', $RoomID)->first();
		
		return view('dashboards.users.checkroom', ['view' => $data], ['view2' => $room]);
	}
}
