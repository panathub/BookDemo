<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use App\Models\Report;
use DB;
use DataTables;

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
        
    $check_start = $request->Booking_start;
    $check_end = $request->Booking_end;
    $check_room = $request->RoomID;

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

    if(!empty($datacheck)) {

        return response()->json(['code'=>2,'msg'=>'มีการจองอยู่แล้ว']);
       // return dd($datacheck);

    }else{
        
        $addbook = new Bookings();
        $addbook->id = \Auth::user()->id;
        $addbook->RoomID = $request->RoomID;
        $addbook->BookingTitle = $request->BookingTitle;
        $addbook->BookingAmount = $request->BookingAmount;
        $addbook->Booking_start = $request->Booking_start;
        $addbook->Booking_end = $request->Booking_end;
        $addbook->BookingDetail = $request->BookingDetail;
        $addbook->RoomStatus = 2;

        $addreport = new Report();
        $addreport->id = \Auth::user()->id;
        $addreport->RoomID = $request->RoomID;
        $addreport->DepartmentID = $request->DepartmentID;
        $addreport->BookingTitle = $request->BookingTitle;
        $addreport->BookingAmount = $request->BookingAmount;
        $addreport->Booking_start = $request->Booking_start;
        $addreport->Booking_end = $request->Booking_end;
        $addreport->BookingDetail = $request->BookingDetail;
        $addreport->RoomStatus = 2;
        $addreport->save();

        $query = $addbook->save();
        //echo $query;
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

            $sql="SELECT users.name,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
            INNER JOIN users ON users.id = bookings.id
            INNER JOIN rooms ON rooms.RoomID = bookings.RoomID
            LEFT OUTER JOIN department ON department.DepartmentID = bookings.DepartmentID
            WHERE users.id = '$userID'
            ORDER BY bookings.BookingID DESC";  

            $data=DB::select($sql); 
                    return DataTables::of($data)
                     ->addIndexColumn()
                     ->addColumn('actions', function($row){
                         return '      
                                 <button class="btn btn-sm btn-info" data-id="'.$row->BookingID.'" id="infoBookingBtn">
                                 <i class="fas fa-info-circle"></i></button>
                                 <button class="btn btn-sm btn-primary" data-id="'.$row->BookingID.'" id="editBookingBtn">
                                 <i class="fas fa-edit"></i></button>
                                 <button class="btn btn-sm btn-danger" data-id="'.$row->BookingID.'" id="deleteBookingBtn">
                                 <i class="fas fa-trash-alt"></i></button>
                                 ';
                     })
                     ->rawColumns(['actions'])
                     ->make(true);  
            
      }
      
          //GET BOOKING DETAILS
    public function getUserBookingDetails(Request $request){
        $booking_id = $request->booking_id;
    
        $sql="SELECT users.*,rooms.*,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID OR department.DepartmentID = bookings.DepartmentID
        WHERE bookings.BookingID ='$booking_id'";
        $bookingDetails=DB::select($sql)[0];
    
        return response()->json(['details'=>$bookingDetails]);
    }

        //UPDATE BOOKING DETAILS
        public function updateUserBookingDetails(Request $request){
            $booking_id = $request->bkid;
        
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
            
                $check = "SELECT * FROM `bookings` WHERE RoomID = '$check_room' AND RoomStatus = '2'
                AND ( 
                    (`Booking_start` BETWEEN '$check_start' AND '$check_end') 
                OR 
                    (`Booking_end` BETWEEN '$check_start' AND '$check_end' )
                OR
                    ('$check_start' BETWEEN `Booking_start` AND `Booking_end`)
                OR
                    ('$check_end' BETWEEN `Booking_start` AND `Booking_end`))";

                $datacheck = DB::select($check);

                if(!empty($datacheck)) {

                    return response()->json(['code'=>2,'msg'=>'มีการจองรอยืนยัน']);
   // return dd($datacheck);

                }else{                  
                 
                $addbook = Bookings::find($booking_id);
                $addbook->RoomID = $request->RoomID;
                $addbook->BookingTitle = $request->BookingTitle;
                $addbook->BookingAmount = $request->BookingAmount;
                $addbook->Booking_start = $request->Booking_start;
                $addbook->Booking_end = $request->Booking_end;
                $addbook->BookingDetail = $request->BookingDetail;
                $query = $addbook->save();
        
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
