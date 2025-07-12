<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class experience extends Model
{
    protected $table = 'experience'; 

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
