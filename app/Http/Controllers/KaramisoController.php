<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use DB;
use App\Models\Modal;


class KaramisoController extends Controller
{

  public function index(){
      return view('karamiso');
  }

    public function getBookingKara(Request $request){
        //$booking_id = $request->booking_id;
       // $RoomID = $request->room_id;
        $today = date('Y-m-d H:i');
        $sql ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID
        WHERE rooms.RoomID = '63' And bookings.VerifyStatus = '1'
        ORDER BY Booking_start ASC";
        $homeBooking=DB::select($sql)[0];
        
       return response()->json(['details'=>$homeBooking]); 
       //return view('noble', ['views'=> $homeBookingDetails]);
        
   
    }

    public function getBookingKara2TEST(Request $request){
        //$booking_id = $request->booking_id;
       // $RoomID = $request->room_id;
        $today = date('Y-m-d H:i');
        $sql ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        WHERE rooms.RoomID = '63' And bookings.VerifyStatus = '1' 
        ORDER BY Booking_start ASC";
        $homeBooking=DB::select($sql)[0];

     
        $homeBookings = $homeBooking->BookingID;

        $sql3 ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        WHERE rooms.RoomID = '63' And bookings.BookingID != $homeBookings And bookings.VerifyStatus = '1' 
        ORDER BY Booking_start ASC";
        
        if(DB::select($sql3) == null){
            $sql2 ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
            INNER JOIN users ON users.id = bookings.id 
            INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
            LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
            WHERE rooms.RoomID = '63' And bookings.VerifyStatus = '1' 
            ORDER BY Booking_start ASC";
            $homeBookingTEST=DB::select($sql2)[0];
            
           return response()->json(['details'=>$homeBookingTEST]); 
        }else{
            
        $homeBookingTEST=DB::select($sql3)[0];
            return response()->json(['details'=>$homeBookingTEST]); 
       //return view('noble', ['views'=> $homeBookingDetails]);
        }

    }

    public function deleteBookingKaramiso(Request $request){

        $sql ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID
        WHERE rooms.RoomID = '63' And bookings.VerifyStatus = '1'
        ORDER BY Booking_start ASC";
        $homeBooking=DB::select($sql)[0];

     
        $homeBookings = $homeBooking->BookingID;



        $sql2 = " DELETE FROM bookings WHERE BookingID = $homeBookings ";
        $Booking = DB::delete($sql2);
        return response()->json($Booking);
    
    }

  
    public function verifyMeeting(Request $request){
        $booking_id = $request->bkid;

        $pass = Bookings::find($booking_id);
        $pass->BookingStatus = 1;
        
        $query = $pass->save();
    
            if($query){
                return response()->json(['code'=>1, 'msg'=>'ยืนยันการจองห้องประชุม']);
            }else{
                return response()->json(['code'=>0, 'msg'=>'Something went wrong']);
            }

    }

    //GET MODAL DETAILS
    public function getNotiModal(Request $request){
        $m_id = $request->m_id;
        
        $sql = "SELECT * FROM modal";
        $modalDetails=DB::select($sql)[0];
        return response()->json(['details'=>$modalDetails]);
        
            }
}
