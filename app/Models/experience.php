<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class experience extends Model
{
    protected $table = 'experience'; 
    protected $fillable = ['job_title', 'company_name', 'start_date', 'end_date', 'student_id'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
