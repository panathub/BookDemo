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

		//$event = Bookings::select('BookingTitle as title', 'Booking_start as start', 'Booking_end as end','BookingID')->get();
		//return response()->json($event);

		//$event=Bookings::Latest()->get();
		//return response()->json($event);

		$events = array();
		$bookings = Bookings::with('room')->get();
		$color = null;

		foreach ($bookings as $booking) {

			switch ($booking->room->RoomName) {
				case 'Karamiso':
					$color = '#ff512f';
					break;
				case 'Sukiyaki':
					$color = '#ffcc00';
					break;
				case 'Tonkotsu':
					$color = '#00DBDE';
					break;
				case 'Kinoko':
					$color = '#FF3CAC';
					break;
				case 'Shabushabu':
					$color = '#2B86C5';
					break;
				default:
					$color = '#000000';
			}
			if ($booking->RoomStatus == 2) {
				$events[] = [
					'id' => $booking->BookingID,
					'title' => $booking->BookingTitle,
					'start' => $booking->Booking_start,
					'end' => $booking->Booking_end,
					'color' => $color ? $color : ''
				];
			}
		}

		return response()->json($events);
	}

	// GET ALL BOOKING
	public function getBookingIndex()
	{
		$month = Carbon::now()->timezone('Asia/Bangkok');
		$databookings = Bookings::select('users.*', 'rooms.*', 'department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->whereMonth('Booking_start', $month)
			->whereDate('Booking_start', '<=', $month)
			->get();
		return DataTables::of($databookings)
			->addIndexColumn()
			->addColumn('actions', function ($row) {
				return ' <button class="btn btn-sm btn-default" data-id="' . $row->BookingID . '" id="infoBookingBtn">
                                 <i class="fas fa-cog"></i></button>
                                 ';
			})
			->rawColumns(['actions'])
			->make(true);
	}

	// GET ALL BOOKING ADMIN
	public function getBookingIndexAdmin()
	{
		$month = Carbon::now()->timezone('Asia/Bangkok');
		$data = Bookings::select('users.name', 'rooms.RoomName', 'department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->where('bookings.RoomStatus', 2)
			->whereMonth('Booking_start', $month)
			->whereDate('Booking_start', '<=', $month)
			->orderBy('Booking_start','asc')
			->withTrashed()
			->get();
		return DataTables::of($data)
			->addIndexColumn()
			->addColumn('actions', function ($row) {
				
				if ($row->VerifyStatus == 1) {
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
			->rawColumns(['actions', 'checkbox'])
			->make(true);
	}

	public function getBookingIndexAdminV2()
	{
		$month = Carbon::now()->timezone('Asia/Bangkok');
		$data = Bookings::select('users.name', 'rooms.RoomName', 'department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->where('bookings.RoomStatus', 2)
			->whereMonth('Booking_start', $month)
			->whereDate('Booking_start', '>=', $month)
			->orderBy('Booking_start','asc')
			->withTrashed()
			->get();
		return DataTables::of($data)
			->addIndexColumn()
			->addColumn('actions', function ($row) {

				return ' <button class="btn btn-sm btn-info" data-id="' . $row->BookingID . '" id="infoBookingBtn">
                        <i class="fas fa-info-circle"></i></button>
                        ';
			})
			->rawColumns(['actions'])
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
