<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use DB;
use DataTables;

class FullCalendarController extends Controller
{
    public function index(){

        //$event = Bookings::select('BookingTitle as title', 'Booking_start as start', 'Booking_end as end','BookingID')->get();
        //return response()->json($event);
        
        //$event=Bookings::Latest()->get();
       //return response()->json($event);

       $events = array();
       $bookings = Bookings::all();
       foreach($bookings as $booking) {
           $events[] = [
                 'id'=>$booking->BookingID,
                'title' => $booking->BookingTitle,
                'start' => $booking->Booking_start,
                'end' => $booking->Booking_end,
                
           ];
       }
       
       return response()->json($events); 
       
    }

        // GET ALL BOOKING
        public function getBookingIndex(Request $request){

            date_default_timezone_set('Asia/Bangkok');
            $month = date('m');

            $sql="SELECT users.name,rooms.*,department.DepartmentName,bookings.* FROM bookings 
            INNER JOIN users ON users.id = bookings.id
            INNER JOIN rooms ON rooms.RoomID = bookings.RoomID
            LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
            WHERE MONTH(Booking_start) = $month";  
            $databookings=DB::select($sql); 
                     return DataTables::of($databookings)
                     ->addIndexColumn()
                     ->addColumn('actions', function($row){
                         return ' <button class="btn btn-sm btn-info" data-id="'.$row->BookingID.'" id="infoBookingBtn">
                                 <i class="fas fa-info-circle"></i> รายละเอียด</button>
                                 ';
                     })
                     ->rawColumns(['actions'])
                     ->make(true);    
            
      }    

       // GET ALL BOOKING ADMIN
       public function getBookingIndexAdmin(Request $request){

        date_default_timezone_set('Asia/Bangkok');
        $month = date('m');

        $sql="SELECT users.name,rooms.*,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID
        WHERE MONTH(Booking_start) = $month";  
        $databookings=DB::select($sql); 
                 return DataTables::of($databookings)
                 ->addIndexColumn()
                 ->addColumn('actions', function($row){
                     if($row->VerifyStatus == 1){
                        return ' <button class="btn btn-sm btn-info" data-id="'.$row->BookingID.'" id="infoBookingBtn">
                             <i class="fas fa-info-circle"></i> รายละเอียด</button>
                             <button class="btn btn-sm btn-primary" data-id="'.$row->BookingID.'" id="editBookingBtn">
                             <i class="fas fas fa-edit"></i> แก้ไข</button>
                             <button class="btn btn-sm btn-danger" data-id="'.$row->BookingID.'" id="deleteBookingBtn">
                             <i class="fas fa-trash-alt"></i> ลบ</button>
                             ';
                     }else{
                        return ' <button class="btn btn-sm btn-info" data-id="'.$row->BookingID.'" id="infoBookingBtn">
                        <i class="fas fa-info-circle"></i> รายละเอียด</button>
                        ';
                     }
                     
                 })
                 ->rawColumns(['actions'])
                 ->make(true);    
        
  }        

                //GET BOOKING DETAILS
    public function getBookingIndexDetails(Request $request){
        $booking_id = $request->booking_id;
    
        $sql="SELECT users.*,rooms.*,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID
        WHERE bookings.BookingID ='$booking_id'";
        $bookingDetails=DB::select($sql)[0];
    
        return response()->json(['details'=>$bookingDetails]);
    }

    
}
