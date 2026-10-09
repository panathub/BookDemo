<?php

namespace App\Http\Controllers;

use App\Enums\BookingState;
use App\Exports\BookingsExport;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Bookings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManageBookingController extends Controller
{
    public function index()
    {
        return view('dashboards.admins.managebooking');
    }

    public function getBookingList()
    {

        $databookings = Bookings::select('users.name', 'rooms.RoomName', 'rooms.RoomNumber', 'department.DepartmentName', 'bookings.*')
            ->join('users', 'bookings.id', '=', 'users.id')
            ->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
            ->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
            ->where('VerifyStatus', 0)
            ->orderByDesc('BookingID')
            ->get();

        return DataTables::of($databookings)
            ->addIndexColumn()
            ->addColumn('room_badge', function ($data) {
                return '<span class="badge badge-md" style="color: #fff; background-color: '.$data->RoomNumber.';">'
                .$data->RoomName.
                '</span>';
            })
            ->addColumn('actions', function ($row) {
                return '
                                 <button class="btn btn-sm btn-success" data-id="'.$row->BookingID.'" id="verifyBookingBtn">
                                 อนุมัติ</button>
                                 <button class="btn btn-sm btn-danger" data-id="'.$row->BookingID.'" id="cancleBookingBtn">
                                 ไม่อนุมัติ</button>
                                 <button class="btn btn-sm btn-info" data-id="'.$row->BookingID.'" id="infoBookingBtn">
                                 <i class="fas fa-info-circle"></i></button>
                                 <button class="btn btn-sm btn-primary" data-id="'.$row->BookingID.'" id="editBookingBtn">
                                 <i class="fas fa-edit"></i></button>
                                 ';
            })
            ->rawColumns(['actions', 'room_badge'])
            ->make(true);
    }

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

    public function updateBookingDetails(StoreBookingRequest $request): JsonResponse
    {
        Bookings::findOrFail($request->integer('bkid'))
            ->reschedule($request->room(), $request->slot(), $request->attrs());

        return response()->json(['code' => 1, 'msg' => 'อัพเดทการจองเรียบร้อย']);
    }

    public function deleteBooking(Request $request): JsonResponse
    {
        $this->cancelBooking($request->integer('booking_id'));

        return response()->json(['code' => 1, 'msg' => 'ลบการจองเรียบร้อย']);
    }

    public function verifyBookingDetails(Request $request): JsonResponse
    {
        Bookings::findOrFail($request->integer('booking_id'))->approve();

        return response()->json(['code' => 1, 'msg' => 'อนุมัตการจองเรียบร้อย']);
    }

    public function cancleBookingDetails(Request $request): JsonResponse
    {
        $this->cancelBooking($request->integer('booking_id'));

        return response()->json(['code' => 1, 'msg' => 'ยกเลิกการจองเรียบร้อย']);
    }

    public function deleteSelectedBooking(Request $request): JsonResponse
    {
        Bookings::findMany($request->input('booking_id', []))->each->cancel();

        return response()->json(['code' => 1, 'msg' => 'Bookings have been delete']);
    }

    public function verifyMeeting(Request $request): JsonResponse
    {
        Bookings::findOrFail($request->integer('bkid'))->confirmMeeting();

        return response()->json(['code' => 1, 'msg' => 'ยืนยันการจองห้องประชุม']);
    }

    private function cancelBooking(int $id): void
    {
        $booking = Bookings::withTrashed()->findOrFail($id);
        abort_if($booking->state === BookingState::Expired, 404);
        $booking->cancel();
    }

    public function exportExcel(Request $request)
    {
        return (new BookingsExport($request->start_date, $request->end_date, $request->room))->download('รายงานการจองห้องประชุม ('.$request->start_date.'_'.$request->end_date.').xlsx');
    }
}
