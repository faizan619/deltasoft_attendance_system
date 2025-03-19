<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    public $timestamps = false;
    
    public function attendanceLogs(){
        return $this->hasMany(Attendance::class,'emp_id','id');
    }
}
