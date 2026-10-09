<?php

namespace App\Http\Controllers;

use App\Enums\BookingState;
use App\Exceptions\BookingRefused;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Bookings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    public function index()
    {
        return view('dashboards.users.booking');
    }

    public function addUserBooking(StoreBookingRequest $request): JsonResponse
    {
        Bookings::request($request->user(), $request->room(), $request->slot(), $request->attrs());

        return response()->json(['code' => 1, 'msg' => 'เพิ่มการจองเรียบร้อย']);
    }

    public function getUserBookingList()
    {
        $userID = \Auth::user()->id;

        $dataUserBookingList = Bookings::select('users.name', 'rooms.RoomName', 'department.DepartmentName', 'bookings.*')
            ->join('users', 'bookings.id', '=', 'users.id')
            ->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
            ->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
            ->where('users.id', '=', $userID)
            ->get();

        return DataTables::of($dataUserBookingList)
            ->addIndexColumn()
            ->addColumn('actions', function ($row) {
                if ($row->VerifyStatus == 1 || $row->VerifyStatus == 2) {
                    return '';
                } else {
                    return '
                                 <button class="btn btn-sm btn-info" data-id="'.$row->BookingID.'" id="infoBookingBtn">
                                 <i class="fas fa-info-circle"></i></button>
                                 <button class="btn btn-sm btn-primary" data-id="'.$row->BookingID.'" id="editBookingBtn">
                                 <i class="fas fa-edit"></i></button>
                                 <button class="btn btn-sm btn-danger" data-id="'.$row->BookingID.'" id="deleteBookingBtn">
                                 <i class="fas fa-trash-alt"></i></button>
                                 ';
                }
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getUserBookingDetails(Request $request)
    {
        $booking_id = $request->booking_id;

        $dataUserBookingDetail = Bookings::select('users.*', 'rooms.*', 'department.DepartmentName', 'bookings.*')
            ->join('users', 'bookings.id', '=', 'users.id')
            ->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
            ->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
            ->where('bookings.BookingID', '=', $booking_id)
            ->first();

        return response()->json(['details' => $dataUserBookingDetail]);
    }

    public function updateUserBookingDetails(StoreBookingRequest $request): JsonResponse
    {
        $this->ownPendingBooking($request, $request->integer('bkid'))
            ->reschedule($request->room(), $request->slot(), $request->attrs());

        return response()->json(['code' => 1, 'msg' => 'อัพเดทการจองเรียบร้อย']);
    }

    public function deleteUserBooking(Request $request): JsonResponse
    {
        $this->ownPendingBooking($request, $request->integer('booking_id'))->forceDelete();

        return response()->json(['code' => 1, 'msg' => 'ลบการจองเรียบร้อย']);
    }

    private function ownPendingBooking(Request $request, int $id): Bookings
    {
        $booking = Bookings::findOrFail($id);
        if (! $booking->ownedBy($request->user()) || $booking->state !== BookingState::Requested) {
            throw BookingRefused::notEditable();
        }

        return $booking;
    }
}
