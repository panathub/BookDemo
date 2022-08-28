<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table='department';//Ignore automatically add "s" into class name to be table name    
    protected $primaryKey='DepartmentID'; //Ignore automatically query with id as primary key
    public $timestamps = false; // Ignore automatically add create_at/update_at fields into table

    protected $fillable = [
        'DepartmentName',
    ];

    public function bookings() {
        return $this->hasMany(Bookings::class,'BookingID','DepartmentID');
    }

	public function users() {
		return $this->hasMany(User::class, 'DepartmentID');
	}

}
