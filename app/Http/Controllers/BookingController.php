<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use App\Models\Report;
use DB;
use DataTables;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(){
        return view('dashboards.users.booking');
    }

           //ADD NEW BOOKING
   public function addUserBooking(Request $request){

    $validator = \Validator::make($request->all(),[
        'BookingTitle'=>'required',
        'RoomID'=>'required',
        'BookingAmount'=>'required',
        'Booking_start'=>'required',
        'Booking_end'=>'required',
    ]);

    if(!$validator->passes()){
        return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
    }else{
	
    $check_start = date($request->Booking_start);
    $check_end = date($request->Booking_end);
    $check_room = $request->RoomID;
    $check_amount = $request->BookingAmount;

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

    $checkRoomReserve = Bookings::where('RoomID', '=', $check_room)
                    			->where('RoomStatus', '!=', 0)
                    			->whereBetween('Booking_start', [$check_start, $check_end])
                    			->orWhereBetween('Booking_end', [$check_start, $check_end])
                    			->orWhereRaw('? BETWEEN Booking_start AND Booking_end', [$check_start])
                    			->orWhereRaw('? BETWEEN Booking_start AND Booking_end', [$check_end])
                    			->first();

	$check2 = Room::where('RoomID','=', $check_room)->pluck('RoomAmount')->first();
	$myString = str_replace("",'',$check2);
  	
    if($check_amount > $myString) {

        return response()->json(['code'=>2,'msg'=>'จำนวนคนเกินห้องประชุม']);

    }else if(!empty($datacheck)){

        return response()->json(['code'=>3,'msg'=>'มีการจองอยู่แล้ว']);

    }else{
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

        if(!$query){
            return response()->json(['code'=>0,'msg'=>'Something went wrong']);
        }else{
            return response()->json(['code'=>1,'msg'=>'เพิ่มการจองเรียบร้อย']);
        } 
     }  

    }
}
        // GET ALL BOOKING
        public function getUserBookingList(Request $request){
            $userID = \Auth::user()->id;

			$dataUserBookingList = Bookings::select('users.name','rooms.RoomName','department.DepartmentName','bookings.*')
											->join('users', 'bookings.id', '=', 'users.id')
											->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
											->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
											->where('users.id', '=', $userID)
											->get();

                    return DataTables::of($dataUserBookingList)
                     ->addIndexColumn()
                     ->addColumn('actions', function($row){
                         if($row->VerifyStatus == 1 || $row->VerifyStatus == 2){
                            return '';
                         }else{
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
      
          //GET BOOKING DETAILS
    public function getUserBookingDetails(Request $request){
        $booking_id = $request->booking_id;
    
		$dataUserBookingDetail = Bookings::select('users.*','rooms.*','department.DepartmentName','bookings.*')
		->join('users', 'bookings.id', '=', 'users.id')
		->join('rooms', 'bookings.RoomID', '=', 'rooms.RoomID')
		->leftJoin('department', 'users.DepartmentID', '=', 'department.DepartmentID')
		->where('bookings.BookingID', '=', $booking_id)
		->first();
    
        return response()->json(['details'=>$dataUserBookingDetail]);
    }

        //UPDATE BOOKING DETAILS
        public function updateUserBookingDetails(Request $request){
            $booking_id = $request->bkid;
            $report_id = $request->rpid;
        
            $validator = \Validator::make($request->all(),[
                'RoomID'=>'required',
                'BookingTitle'=>'required',
                'BookingAmount'=>'required',
                'Booking_start'=>'required',
                'Booking_end'=>'required',
            ]);
        
            if(!$validator->passes()){
                   return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
            }else{

                $check_start = $request->Booking_start;
                $check_end = $request->Booking_end;
                $check_room = $request->RoomID;
            
                $check = "SELECT * FROM `bookings` WHERE RoomID = '$check_room' AND BookingID != '$booking_id'
                AND ( 
                    (`Booking_start` BETWEEN '$check_start' AND '$check_end') 
                OR 
                    (`Booking_end` BETWEEN '$check_start' AND '$check_end' )
                OR
                    ('$check_start' BETWEEN `Booking_start` AND `Booking_end`)
                OR
                    ('$check_end' BETWEEN `Booking_start` AND `Booking_end`))";

                $datacheck = DB::select($check);

				// $checkRoomReserveUpdate = Bookings::where('RoomID', '=', $check_room)
				// ->where('BookingID', '!=', $booking_id)
				// ->whereBetween('Booking_start', [$check_start, $check_end])
				// ->orWhereBetween('Booking_end', [$check_start, $check_end])
				// ->orWhereRaw('? BETWEEN Booking_start AND Booking_end', [$check_start])
				// ->orWhereRaw('? BETWEEN Booking_start AND Booking_end', [$check_end])
				// ->get();

                if(!empty($datacheck)) {

                    return response()->json(['code'=>2,'msg'=>'มีการจองรอยืนยัน']);
 
                }else{                  
                 
                $addbook = Bookings::find($booking_id);
                $addbook->RoomID = $request->RoomID;
                $addbook->BookingTitle = $request->BookingTitle;
                $addbook->BookingAmount = $request->BookingAmount;
                $addbook->Booking_start = $request->Booking_start;
                $addbook->Booking_end = $request->Booking_end;
                $addbook->BookingDetail = $request->BookingDetail;
                $query = $addbook->save();

                $addreport = Report::find($report_id);
                $addreport->RoomID = $request->RoomID;
                $addreport->BookingTitle = $request->BookingTitle;
                $addreport->BookingAmount = $request->BookingAmount;
                $addreport->Booking_start = $request->Booking_start;
                $addreport->Booking_end = $request->Booking_end;
                $addreport->BookingDetail = $request->BookingDetail;

                $query = $addreport->save();
        
                if($query){
                    return response()->json(['code'=>1, 'msg'=>'อัพเดทการจองเรียบร้อย']);
                   
                }else{
                    return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
                }
            }
        }
    }   
      
            // DELETE BOOKING RECORD
    public function deleteUserBooking(Request $request){
        $booking_id = $request->booking_id;
        $query = Bookings::find($booking_id)->delete();
    
        if($query){
            return response()->json(['code'=>1, 'msg'=>'ลบการจองเรียบร้อย']);
        }else{
            return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
        }
    }
    
}
