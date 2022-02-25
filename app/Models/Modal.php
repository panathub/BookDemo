<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modal extends Model
{
    use HasFactory;

    protected $table='modal';//Ignore automatically add "s" into class name to be table name    
    protected $primaryKey='id'; //Ignore automatically query with id as primary key
    public $timestamps = false; // Ignore automatically add create_at/update_at fields into table

    protected $fillable = ['image','text'];
}
