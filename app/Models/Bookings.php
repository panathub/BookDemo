<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    protected $table='bookings';//Ignore automatically add "s" into class name to be table name    
    protected $primaryKey='BookingID'; //Ignore automatically query with id as primary key
    public $timestamps = false; // Ignore automatically add create_at/update_at fields into table 

    protected $fillable = [
        'BookingTitle',
        'BookingAmount',
        'Booking_start',
        'Booking_end',
        'BookingDetail',
        'BookingStatus', 
        'RoomStatus',
        'VerifyStatus',
        'id',
        'RoomID',
        'DepartmentID',
        'ReportID',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id');
    }

    public function department() {
        return $this->belongsTo(Department::class, 'DepartmentID');
    }

    public function room() {
        return $this->belongsTo(Room::class, 'RoomID');
    }

    public function report() {
        return $this->belongsTo(Report::class, 'ReportID');
    }
   
}