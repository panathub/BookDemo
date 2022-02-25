<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessories extends Model
{
    protected $table='accessories';//Ignore automatically add "s" into class name to be table name    
    protected $primaryKey='AccessoriesID'; //Ignore automatically query with id as primary key
    public $timestamps = false; // Ignore automatically add create_at/update_at fields into table

    protected $fillable = ['Name','Quantity','Image_acc'];
}
