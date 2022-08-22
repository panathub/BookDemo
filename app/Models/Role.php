<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table='role';//Ignore automatically add "s" into class name to be table name    
    protected $primaryKey='roleID'; //Ignore automatically query with id as primary key
    public $timestamps = false; // Ignore automatically add create_at/update_at fields into tablE

	public function users() {

		return $this->hasMany(User::class);
	}
}
