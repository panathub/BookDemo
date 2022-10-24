<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use DB;
use App\Jobs\RunBooking;
use Carbon\Carbon;


class TonkotsuController extends Controller
{

  public function index(){
      return view('tonkotsu');
  }

    public function getBookingTonkotsu(Request $request){
        //$booking_id = $request->booking_id;
       // $RoomID = $request->room_id;
        $today = date('Y-m-d H:i');
        $sql ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        WHERE rooms.RoomID = '60' And bookings.VerifyStatus = '1'
        ORDER BY Booking_start ASC";
        $homeBooking=DB::select($sql)[0];
        
       return response()->json(['details'=>$homeBooking]); 
       //return view('noble', ['views'=> $homeBookingDetails]);
        
   
    }

    public function getBookingTonkotsu2TEST(Request $request){
        //$booking_id = $request->booking_id;
       // $RoomID = $request->room_id;
        $today = date('Y-m-d H:i');
        $sql ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        WHERE rooms.RoomID = '60' And bookings.VerifyStatus = '1'
        ORDER BY Booking_start ASC";
        $homeBooking=DB::select($sql)[0];

     
        $homeBookings = $homeBooking->BookingID;

        $sql3 ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        WHERE rooms.RoomID = '60' And bookings.BookingID != $homeBookings And bookings.VerifyStatus = '1'
        ORDER BY Booking_start ASC";
        
        if(DB::select($sql3) == null){
            $sql2 ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
            INNER JOIN users ON users.id = bookings.id 
            INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
            LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
            WHERE rooms.RoomID = '60' And bookings.VerifyStatus = '1'
            ORDER BY Booking_start ASC";
            $homeBookingTEST=DB::select($sql2)[0];
            
           return response()->json(['details'=>$homeBookingTEST]); 
        }else{
            
        $homeBookingTEST=DB::select($sql3)[0];
            return response()->json(['details'=>$homeBookingTEST]); 
       //return view('noble', ['views'=> $homeBookingDetails]);
        }

    }

    public function deleteBookingTonkotsu(Request $request){

        $sql ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        WHERE rooms.RoomID = '60' And bookings.VerifyStatus = '1'
        ORDER BY Booking_start ASC";
        $homeBooking=DB::select($sql)[0];

     
        $homeBookings = $homeBooking->BookingID;



        $sql2 = " DELETE FROM bookings WHERE BookingID = $homeBookings ";
        //$sql2 = " UPDATE bookings SET BookingStatus = '3' WHERE BookingID = $homeBookings ";
        $Booking = DB::delete($sql2);
		RunBooking::dispatch($Booking)->delay(Carbon::now()->addMinutes(5));
        return response()->json($Booking);
    
    }

   /* public function getBookingRoom(Request $request , $RoomID){
        
        
        $booking_id = $request->booking_id;
        $today = date('Y-m-d');

        $sql ="SELECT users.*,rooms.RoomName,department.DepartmentName,bookings.* FROM bookings 
        INNER JOIN users ON users.id = bookings.id 
        INNER JOIN rooms ON rooms.RoomID = bookings.RoomID 
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        OR department.DepartmentID = bookings.DepartmentID 
        WHERE bookings.BookingID AND rooms.RoomID = '$RoomID' And bookings.VerifyStatus = 1
        ORDER BY Booking_start ASC , BookingDate DESC";
        $homeBookingDetails=DB::select($sql);
        //$homeBookingDetails=DB::select($sql)[0];
    
          
       //return dd($homeBookingDetails);
        //return response()->json(['details'=>$homeBookingDetails]); 
        return view('noble', ['views'=> $homeBookingDetails]);
   
    }    */

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

    
}
