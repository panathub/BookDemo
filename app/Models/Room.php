<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Room extends Model
{
    protected $table='rooms';//Ignore automatically add "s" into class name to be table name    
    protected $primaryKey='RoomID'; //Ignore automatically query with id as primary key
    public $timestamps = false; // Ignore automatically add create_at/update_at fields into tabl 
   
    protected $fillable = ['RoomName','RoomNumber','RoomStatus','Image_room'];

    public function bookings() {
        return $this->hasMany(Bookings::class,'BookingID','RoomID');
    }
}
