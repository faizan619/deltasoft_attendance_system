<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public $timestamps = false;
    protected $table = "attendance";

    function empName(){
        return $this->belongsTo(User::class,'emp_id','id');
    }
}
