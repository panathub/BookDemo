<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table='reports';//Ignore automatically add "s" into class name to be table name    
    protected $primaryKey='ReportID'; //Ignore automatically query with id as primary key
    public $timestamps = false; // Ignore automatically add creat

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
    ];

    
    public function user() {
        return $this->belongsTo(User::class, 'id');
    }

    public function room() {
        return $this->belongsTo(Room::class, 'RoomID');
    }

    public function bookings() {
        return $this->hasOne(Bookings::class);
    }
}
