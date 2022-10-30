<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use App\Models\Report;
use DataTables;
use Carbon\Carbon;
use DB;
use App\Jobs\RunBooking;
use Phattarachai\LineNotify\Facade\Line;

class ManageBookingController extends Controller
{
	public function index()
	{
		return view('dashboards.admins.managebooking');
	}

	//ADD NEW BOOKING
	public function addBooking(Request $request)
	{

		$validator = \Validator::make($request->all(), [
			'BookingTitle' => 'required',
			'RoomID' => 'required',
			'BookingAmount' => 'required',
			'Booking_start' => 'required',
			'Booking_end' => 'required',
		]);

		if (!$validator->passes()) {
			return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
		} else {

			$check_start = Carbon::parse($request->Booking_start);
			$check_end = Carbon::parse($request->Booking_end);
			$check_room = $request->RoomID;
			$check_amount = $request->BookingAmount;
			// $checkRoomReserve = Bookings::where('RoomID', '=', $check_room)
			// 	->where('RoomStatus', '!=', 0)
			// 	->where(function ($query) use ($check_start, $check_end) {
			// 		$query->where(function ($q) use ($check_start, $check_end) {
			// 			$q->whereBetween('Booking_start', [$check_start, $check_end]);
			// 		});
			// 		$query->orWhere(function ($q2) use ($check_start, $check_end) {
			// 			$q2->whereBetween('Booking_end', [$check_start, $check_end]);
			// 		});
			// 	})->exists();

			$check = "SELECT * FROM `bookings` WHERE RoomID = '$check_room' AND RoomStatus != '0'
				AND ( 
					(`Booking_start` BETWEEN '$check_start' AND '$check_end') 
				OR 
					(`Booking_end` BETWEEN '$check_start' AND '$check_end' )
				OR
					('$check_start' BETWEEN `Booking_start` AND `Booking_end`)
				OR
					('$check_end' BETWEEN `Booking_start` AND `Booking_end`))";

			$datacheck = DB::select($check);

			$check2 = Room::where('RoomID', '=', $check_room)->pluck('RoomAmount')->first();
			$myString = str_replace("", '', $check2);

			if ($check_amount > $myString) {
				return response()->json(['code' => 2, 'msg' => 'จำนวนคนเกินห้องประชุม']);
			} else if (!empty($datacheck)) {
				return response()->json(['code' => 3, 'msg' => 'มีการจองอยู่แล้ว']);
			} else {

				$addreport = new Report();
				$addreport->id = \Auth::user()->id;
				$addreport->RoomID = $request->RoomID;

				$addreport->BookingTitle = $request->BookingTitle;
				$addreport->BookingAmount = $request->BookingAmount;
				$addreport->Booking_start = $request->Booking_start;
				$addreport->Booking_end = $request->Booking_end;
				$addreport->BookingDetail = $request->BookingDetail;
				$addreport->RoomStatus = 2;
				$addreport->save();

				$addbook = new Bookings();
				$addbook->ReportID = $addreport->ReportID;
				$addbook->id = \Auth::user()->id;
				$addbook->RoomID = $request->RoomID;

				$addbook->BookingTitle = $request->BookingTitle;
				$addbook->BookingAmount = $request->BookingAmount;
				$addbook->Booking_start = $request->Booking_start;
				$addbook->Booking_end = $request->Booking_end;
				$addbook->BookingDetail = $request->BookingDetail;
				$addbook->RoomStatus = 2;

				$query = $addbook->save();
				if (!$query) {
					return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
				} else {
					return response()->json(['code' => 1, 'msg' => 'เพิ่มการจองเรียบร้อย']);
				}
			}
		}
	}

	// GET ALL BOOKING
	public function getBookingList(Request $request)
	{

		$databookings = Bookings::select('users.name', 'rooms.RoomName', 'department.DepartmentName', 'bookings.*')
			->join('users', 'bookings.id', '=', 'users.id')
			->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
			->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
			->where('VerifyStatus', 0)
			->orderByDesc('BookingID')
			->get();
		return DataTables::of($databookings)
			->addIndexColumn()
			->addColumn('actions', function ($row) {
				return '
                                 <button class="btn btn-sm btn-success" data-id="' . $row->BookingID . '" id="verifyBookingBtn">
                                 อนุมัติ</button>
                                 <button class="btn btn-sm btn-danger" data-id="' . $row->BookingID . '" id="cancleBookingBtn">
                                 ไม่อนุมัติ</button>
                                 <button class="btn btn-sm btn-info" data-id="' . $row->BookingID . '" id="infoBookingBtn">
                                 <i class="fas fa-info-circle"></i></button>
                                 <button class="btn btn-sm btn-primary" data-id="' . $row->BookingID . '" id="editBookingBtn">
                                 <i class="fas fa-edit"></i></button>
                                 ';
			})
			->rawColumns(['actions'])
			->make(true);
	}

	//GET BOOKING DETAILS
	public function getBookingDetails(Request $request)
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

	//UPDATE BOOKING DETAILS
	public function updateBookingDetails(Request $request)
	{
		$booking_id = $request->bkid;
		$report_id = $request->rpid;

		$validator = \Validator::make($request->all(), [

			'RoomID' => 'required',
			'BookingTitle' => 'required',
			'BookingAmount' => 'required',
			'Booking_start' => 'required',
			'Booking_end' => 'required',

		]);

		if (!$validator->passes()) {
			return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
		} else {

			$addbook = Bookings::find($booking_id);
			$addbook->RoomID = $request->RoomID;
			$addbook->BookingTitle = $request->BookingTitle;
			$addbook->BookingAmount = $request->BookingAmount;
			$addbook->Booking_start = $request->Booking_start;
			$addbook->Booking_end = $request->Booking_end;
			$addbook->BookingDetail = $request->BookingDetail;
			$addbook->save();

			$addreport = Report::find($report_id);
			$addreport->RoomID = $request->RoomID;
			$addreport->BookingTitle = $request->BookingTitle;
			$addreport->BookingAmount = $request->BookingAmount;
			$addreport->Booking_start = $request->Booking_start;
			$addreport->Booking_end = $request->Booking_end;
			$addreport->BookingDetail = $request->BookingDetail;


			$query = $addreport->save();

			if ($query) {
				return response()->json(['code' => 1, 'msg' => 'อัพเดทการจองเรียบร้อย']);
			} else {
				return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
			}
		}
	}

	// DELETE BOOKING RECORD
	public function deleteBooking(Request $request)
	{
		$booking_id = $request->booking_id;
		$query = Bookings::find($booking_id)->delete();

		if ($query) {
			return response()->json(['code' => 1, 'msg' => 'ลบการจองเรียบร้อย']);
		} else {
			return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
		}
	}

	public function verifyBookingDetails(Request $request)
	{
		$booking_id = $request->booking_id;

		$pass = Bookings::find($booking_id);
		$pass2 = Bookings::with(['user' => function ($test) {
			$test->select('*')->leftjoin('department', 'users.DepartmentID', "=", 'department.DepartmentID');
		}, 'room'])->find($booking_id);


		$pass->RoomStatus = 2;
		$pass->VerifyStatus = 1;
		$pass->BookingStatus = 1;

		$title = $pass2->BookingTitle;
		$start = $pass2->Booking_start;
		$end = $pass2->Booking_end;
		$detail = $pass2->BookingDetail ? $pass2->BookingDetail : '-';
		$roomName = $pass2->room->RoomName;
		$userName = $pass2->user->name;
		$departmentName = $pass2->user->DepartmentName;

		$formatDelete = Carbon::parse($pass2->Booking_end)->timezone('Asia/Bangkok');
		$query = $pass->save();
		$sMessage = "📣ปุกาศ✨ " . "\n" . "หัวข้อประชุม: " . $title . "\n" . "ห้อง: " . $roomName . "\n"
			. "ผู้จอง: " . $userName . "\n" . "แผนก: " . $departmentName . "\n" . "เวลาเริ่ม: " . $start . "\n"
			. "เวลาสิ้นสุด: " . $end . "\n" . "รายละเอียด: " . $detail . "\n" . "ได้รับการอนุมัติจาก Admin 🔥";
		if ($query) {
			Line::sticker(446, 1989)->send($sMessage);
			RunBooking::dispatch($pass2)->delay($formatDelete);

			return response()->json(['code' => 1, 'msg' => 'อนุมัตการจองเรียบร้อย']);
		} else {
			return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
		}
	}

	public function cancleBookingDetails(Request $request)
	{
		$booking_id = $request->booking_id;

		$pass2 = Bookings::with(['user' => function ($test) {
			$test->select('*')->leftjoin('department', 'users.DepartmentID', "=", 'department.DepartmentID');
		}, 'room'])->find($booking_id);

		$pass2->VerifyStatus = 2;

		$title = $pass2->BookingTitle;
		$start = $pass2->Booking_start;
		$end = $pass2->Booking_end;
		$roomName = $pass2->room->RoomName;
		$userName = $pass2->user->name;
		$departmentName = $pass2->user->DepartmentName;

		$query = $pass2->save();
		$sMessage = "📣✨ ปุกาศจ้า 📣✨" . "\n" . "หัวข้อประชุม: " . $title . "\n" . "ห้อง: " . $roomName . "\n"
			. "ผู้จอง: " . $userName . "\n" . "แผนก: " . $departmentName . "\n" . "เวลาเริ่ม: " . $start . "\n"
			. "เวลาสิ้นสุด: " . $end . "\n" . "โดนยกเลิกจาก Admin 😢";
		$query = Bookings::find($booking_id)->delete();
		if ($query) {
			Line::sticker(446, 2008)->send($sMessage);  
			return response()->json(['code' => 1, 'msg' => 'ยกเลิกการจองเรียบร้อย']);
		} else {
			return response()->json(['code' => 0, 'msg' => 'Something went wrong']);
		}
	}

	public function deleteSelectedBooking(Request $request)
	{
		$booking_id = $request->booking_id;
		$booking = Bookings::whereIn('BookingID', $booking_id);
		$booking->update(['VerifyStatus' => 2]);
		$booking->delete();
		return response()->json(['code' => 1, 'msg' => 'Bookings have been delete']);
	}
}
