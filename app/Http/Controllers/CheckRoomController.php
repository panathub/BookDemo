<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings;
use App\Models\User;
use App\Models\Room;
use DB;
use DataTables;

class CheckRoomController extends Controller
{
    public function index(){
        return view('dashboards.users.checkroom-nabezo');
    }

    //GET BOOKING DETAILS 
    public function view($RoomID){
        
        $sql="SELECT users.*,rooms.*,department.DepartmentName,bookings.*,
        DATE_FORMAT(bookings.Booking_start,'%d/%m/%Y %H:%i')As MyStart,DATE_FORMAT(bookings.Booking_end,'%d/%m/%Y %H:%i')As MyEnd
        FROM bookings 
        LEFT JOIN users ON users.id = bookings.id
        RIGHT JOIN rooms ON rooms.RoomID = bookings.RoomID
        LEFT JOIN department ON department.DepartmentID = users.DepartmentID 
        WHERE rooms.RoomID ='$RoomID'
        ORDER BY Booking_start";
        $data=DB::select($sql);
        $data2=DB::select($sql)[0];
    
        return view('dashboards.users.checkroom', ['view'=> $data], ['view2'=> $data2]);

    }
}
