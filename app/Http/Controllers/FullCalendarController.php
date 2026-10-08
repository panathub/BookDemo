<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use DB;
use DataTables;
use Carbon\Carbon;

class FullCalendarController extends Controller
{
	public function index()
	{
		$events = array();
		$bookings = Bookings::with('room')->get();
		$color = null;

		foreach ($bookings as $booking) {

			if ($booking->RoomStatus == 2) {
				$events[] = [
					'id' => $booking->BookingID,
					'title' => $booking->BookingTitle,
					'start' => $booking->Booking_start,
					'end' => $booking->Booking_end,
					'color' => $booking->room->RoomNumber,
                                        'roomName' => $booking->room->RoomName
				];
			}
		}

		return response()->json($events);
	}

	// GET ALL BOOKING
	public function getBookingIndex()
	{
		$month = Carbon::now()->timezone('Asia/Bangkok');
		$databookings = Bookings::select('users.name', 'rooms.RoomName', 'rooms.RoomNumber','department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->where('bookings.RoomStatus', 2)
			->whereMonth('Booking_start', $month)
			->whereDate('Booking_start', '>=', $month)
			->get();
		return DataTables::of($databookings)
			->addIndexColumn()
			->addColumn('room_badge', function ($data) {
				return '<span class="badge badge-md" style="color: #fff; background-color: ' . $data->RoomNumber . ';">' 
				. $data->RoomName . 
				'</span>';
			})
			->addColumn('actions', function ($row) {
				return ' <button class="btn btn-sm btn-default" data-id="' . $row->BookingID . '" id="infoBookingBtn">
                                 <i class="fas fa-cog"></i></button>
                                 ';
			})
			->rawColumns(['actions', 'room_badge'])
			->make(true);
	}

	// GET ALL BOOKING ADMIN
	public function getBookingIndexAdmin()
	{
		$month = Carbon::now()->timezone('Asia/Bangkok'); 
                $data = Bookings::select('users.name', 'rooms.RoomName', 'rooms.RoomNumber','department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->where('bookings.RoomStatus', 2)
			->whereDate('Booking_start', '>=', $month->startOfMonth())
			->orderBy('Booking_start','asc')
			->withTrashed()
			->get();

		return DataTables::of($data)
			->addIndexColumn()
			->addColumn('room_badge', function ($data) {
				return '<span class="badge badge-md" style="color: #fff; background-color: ' . $data->RoomNumber . ';">' 
				. $data->RoomName . 
				'</span>';
			})
			->addColumn('actions', function ($row) {
				
				if ($row->VerifyStatus == 1 && $row->deleted_at == null) {
					return ' <button class="btn btn-sm btn-info" data-id="' . $row->BookingID . '" id="infoBookingBtn">
                             <i class="fas fa-info-circle"></i></button>
                             <button class="btn btn-sm btn-primary" data-id="' . $row->BookingID . '" id="editBookingBtn">
                             <i class="fas fas fa-edit"></i></button>
                             <button class="btn btn-sm btn-danger" data-id="' . $row->BookingID . '" id="deleteBookingBtn">
                             <i class="fas fa-trash-alt"></i></button>
                             ';
				} else {
					return ' <button class="btn btn-sm btn-info" data-id="' . $row->BookingID . '" id="infoBookingBtn">
                        <i class="fas fa-info-circle"></i></button>
                        ';
				}
			})
			->addColumn('checkbox', function ($row) {
				return '<input type="checkbox" name="booking_checkbox" data-id="' . $row->BookingID . '"><label></label>';
			})
			->rawColumns(['actions', 'checkbox', 'room_badge'])
			->make(true);
	}

	public function getBookingIndexAdminV2()
	{
		$month = Carbon::now()->timezone('Asia/Bangkok');
		$data = Bookings::select('users.name', 'rooms.RoomName', 'rooms.RoomNumber','department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->where('bookings.RoomStatus', 2)
			->whereMonth('Booking_start', $month)
			->whereDate('Booking_start', '>=', $month)
			->whereYear('Booking_start', $month)
			->orderBy('Booking_start','asc')
			->withTrashed()
			->get();
		return DataTables::of($data)
			->addIndexColumn()
			->addColumn('room_badge', function ($data) {
				return '<span class="badge badge-md" style="color: #fff; background-color: ' . $data->RoomNumber . ';">' 
				. $data->RoomName . 
				'</span>';
			})
			->addColumn('actions', function ($row) {

				return ' <button class="btn btn-sm btn-info" data-id="' . $row->BookingID . '" id="infoBookingBtn">
                        <i class="fas fa-info-circle"></i></button>
                        ';
			})
			->rawColumns(['actions', 'room_badge'])
			->make(true);
	}

	//GET BOOKING DETAILS
	public function getBookingIndexDetails(Request $request)
	{
		$booking_id = $request->booking_id;

		$bookingDetails = Bookings::select('users.*', 'rooms.*', 'department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->where('bookings.BookingID', '=', $booking_id)
			->withTrashed()
			->first();
		return response()->json(['details' => $bookingDetails]);
	}
}
